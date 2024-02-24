<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Helpers\TreeHelper;
use App\Models\Menu;
use App\Models\MenuName;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Get;
use App\Helpers\MenuHelper as MenuHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Menu::class;
    public static ?string $table = 'menus';
    protected static ?string $navigationIcon = 'heroicon-o-bars-2';
    protected static ?int $navigationSort = 50;


    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([

                Section::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([

                        Grid::make()
                            ->schema([
                                Select::make('menu_name_id')
                                    ->relationship('menuName', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(session('table_filters.menu_name')) // Получаем из сессии id MenuName
                                    ->required()
                                    ->translateLabel()
                                    ->live()
                                    ->createOptionForm(MenuNameResource::forms()),
                                Select::make('parent_id')
                                    ->label('Parent')
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            // Только для edit и id не должно равно parent_id
                                            if ($get('id') && $get('id') == $value) {
                                                $fail(__('validation.is_invalid'));
                                            }
                                        },
                                        fn (Get $get, $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                            // Только для edit и нельзя сохранить одного из потомков как родителя
                                            if ($get('id') && $record?->descendants?->pluck('id')?->contains($get('id'))) {
                                                $fail(__('validation.is_invalid'));
                                            }
                                        },
                                    ])
                                    ->options(function (Get $get) {
                                        return TreeHelper::treeForSelect(MenuHelper::find(
                                            $get('menu_name_id'),
                                            false,
                                            //MenuHelper::descendantsAndSelf($record)
                                        ));
                                    })
                                    ->disableOptionWhen(function (string $value, ?Menu $record) {
                                        // Получаем родителей
                                        //$ancestors = $record?->ancestors?->pluck('id');
                                        // Получаем потомков
                                        $descendants = $record?->descendants?->pluck('id');
                                        // Добавляем id текущей записи
                                        $id = $record?->id;
                                        if ($id) {
                                            $descendants->push($id);
                                        }
                                        // Выключаем option, которые равны id потомкам или текущему id
                                        return $descendants?->contains($value);
                                    })
                                    ->searchable()
                                    ->translateLabel(),
                                TextInput::make('title')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('link')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('item')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('class')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('target')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('sort')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(65535)
                                    ->default(5000)
                                    ->translateLabel(),
                                /*Select::make('image')
                                    ->options([])
                                    ->searchable()
                                    ->translateLabel(),*/
                                MarkdownEditor::make('content') // Перевод строки \
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'strike',
                                    'link',
                                    'heading',
                                    'codeBlock',
                                    'bulletList',
                                    'orderedList',
                                    'redo',
                                    'table',
                                    'undo',
                                ])
                                    ->translateLabel(),
                                KeyValue::make('attrs')
                                    ->reorderable()
                                    ->translateLabel(),
                            ]),
                    ]),

                Section::make()
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn ($record) => $record === null)
                    ->schema([
                        Toggle::make('active')
                            ->default(false)
                            ->translateLabel(),
                        Placeholder::make('id')
                            ->content(fn ($record): ?int => $record?->id)
                            ->translateLabel(),
                        Placeholder::make('created_at')
                            ->content(fn ($record): ?string => $record?->created_at?->format(FilamentHelper::dateFormat()))
                            ->translateLabel(),
                        Placeholder::make('updated_at')
                            ->content(fn ($record): ?string => $record?->updated_at?->diffForHumans())
                            ->translateLabel(),
                    ]),
                /*Section::make()
                    ->schema([
                        TextInput::make('id')
                            ->disabled()
                            ->translateLabel(),
                        Select::make('menu_name_id')
                            ->relationship('menuName', 'name')
                            ->searchable()
                            ->preload()
                            ->default(session('table_filters.menu_name')) // Получаем из сессии id MenuName
                            ->required()
                            ->translateLabel()
                            ->live()
                            ->createOptionForm(MenuNameResource::forms()),
                        TextInput::make('title')
                            ->maxLength(255)
                            ->translateLabel(),
                        TextInput::make('link')
                            ->maxLength(255)
                            ->translateLabel(),
//                        Select::make('image')
//                            ->options([])
//                            ->searchable()
//                            ->translateLabel(),
                        TextInput::make('item')
                            ->maxLength(255)
                            ->translateLabel(),
                        TextInput::make('class')
                            ->maxLength(255)
                            ->translateLabel(),
                        TextInput::make('target')
                            ->maxLength(255)
                            ->translateLabel(),
                        TextInput::make('sort')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(65535)
                            ->default(5000)
                            ->translateLabel(),
                        MarkdownEditor::make('body') // Перевод строки \
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'codeBlock',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'table',
                                'undo',
                            ])
                            ->translateLabel(),
                        Repeater::make('attrs')
                            ->schema([
                                TextInput::make('attr')
                                    ->translateLabel(),
                            ])
                            ->translateLabel(),
                        Toggle::make('active')
                            ->default(false)
                            ->translateLabel(),
                        Select::make('parent_id')
                            ->label('Parent')
                            ->rules([
                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    // Только для edit и id не должно равно parent_id
                                    if ($get('id') && $get('id') == $value) {
                                        $fail(__('validation.is_invalid'));
                                    }
                                },
                                fn (Get $get, $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                    // Только для edit и нельзя сохранить одного из потомков как родителя
                                    if ($get('id') && $record?->descendants?->pluck('id')?->contains($get('id'))) {
                                        $fail(__('validation.is_invalid'));
                                    }
                                },
                            ])
                            ->options(function (Get $get, Menu $record) {
                                return Tree::select(MenuHelper::find(
                                    $get('menu_name_id'),
                                    false,
                                    MenuHelper::descendantsAndSelf($record)
                                ));
                            })
                            ->searchable()
                            ->translateLabel(),
                    ])
                    ->columns(2),*/
            ]);
    }

    public static function table(Table $table): Table
    {
        $menuNameFirstId = request('tableFilters.menu_name.value') ?: MenuName::first()?->id;
        session()->put('table_filters.menu_name', $menuNameFirstId); // Записываем в сессию, чтобы в create action получить

        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('id')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('link')
                    ->limit(40)
                    //->wrap()
                    ->color('gray')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                IconColumn::make('active')
                    ->boolean()
                    ->toggleable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('menuName.name')
                    ->label('Menu name')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('parent_id')
                    ->label('Parent')
                    /*->state(function () {
                        return '...';
                    })*/
                    ->formatStateUsing(function (?int $state, ?Menu $record) {
                        $title = $record?->menus?->title;
                        if ($title) {
                            $title = ' ' . $title;
                        }
                        return $state . $title;
                    })
                    ->badge()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('sort')
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->badge()
                    ->color('gray')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->badge()
                    ->color('gray')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
                TextColumn::make('deleted_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('menu_name')
                    ->relationship('menuName', 'name', function ($query) {
                        return $query->orderBy('sort');
                    })
                    ->searchable()
                    ->default($menuNameFirstId)
                    ->preload()
                    ->translateLabel(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip(__('Actions')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //RelationManagers\MenuNameRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
