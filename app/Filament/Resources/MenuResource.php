<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages\{CreateMenu, EditMenu, ListMenus};
use Filament\Forms\Components\{Grid, KeyValue, MarkdownEditor, Placeholder, Section, Select, TextInput, Toggle};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\{Form, Get, Set};
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use Closure;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\{Menu, MenuName};
use App\Helpers\MenuHelper;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Helpers\TreeHelper;
//use CodeWithDennis\FilamentSelectTree\SelectTree;

class MenuResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Menu::class;
    public static ?string $table = 'menus';
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
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
                                            id: $get('menu_name_id'),
                                            active: false,
                                            sort: 'id',
                                        ));
                                        //MenuHelper::descendantsAndSelf($record)
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
                                // https://github.com/CodeWithDennis/filament-select-tree
                                /*SelectTree::make('parent_id')
                                    ->label('Parent')
                                    ->placeholder(__('select_category'))
                                    ->emptyLabel(__('nothing_was_found'))
                                    ->relationship('menus', 'id', 'parent_id', fn (Builder $query) => $query->orderBy('id', 'desc'))
                                    ->searchable()
                                    ->withCount()
                                    ->disabledOptions(function (?Menu $record): array {
                                        // Получаем родителей
                                        //$ancestors = $record?->ancestors?->pluck('id');
                                        // Получаем потомков
                                        $descendants = $record?->descendants?->pluck('id');
                                        // Добавляем id текущей записи
                                        if ($record?->id) {
                                            $descendants->push($record->id);
                                        }
                                        // Выключаем option, которые равны id потомкам или текущему id
                                        return $descendants?->toArray() ?: [];
                                    })
                                    ->defaultOpenLevel(2)
                                    ->translateLabel(),*/
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
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('link')
                    ->limit(40)
                    //->wrap()
                    ->color('gray')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
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
                    ->relationship('menuName', 'name', fn (Builder $query) => $query->orderBy('id', 'desc'))
                    ->searchable()
                    ->default($menuNameFirstId)
                    ->preload()
                    ->translateLabel(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])->tooltip(__('Actions')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    /*public static function getRelations(): array
    {
        return [
            //RelationManagers\MenuNameRelationManager::class,
        ];
    }*/

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Добавить колонку в глобальный поиск.
     *
     * @return string[]
     */
    /*public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }*/
}
