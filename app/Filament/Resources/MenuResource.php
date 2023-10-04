<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\Tree;
use App\Models\Menu;
use App\Models\MenuName;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
use App\Helpers\Menu as MenuHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Menu::class;
    protected static ?string $table = 'menus';
    protected static ?string $navigationIcon = 'heroicon-o-bars-2';
    protected static ?int $navigationSort = 50;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
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
                        RichEditor::make('content')
                            ->disableToolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'link',
                                'orderedList',
                                'bulletList',
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
                                /*fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    // Только для edit и нельзя сохранить одного из потомков как родителя
                                    dd($get);
                                },*/
                            ])
                            ->options(fn (Get $get): array => Tree::select(MenuHelper::find($get('menu_name_id'), false)))
                            ->searchable()
                            ->translateLabel(),
                    ])
                    ->columns(2),
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
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('link')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                IconColumn::make('active')
                    ->boolean()
                    ->toggleable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('parent_id')
                    ->label('Parent')
                    //->badge()
                    ->color('gray')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('sort')
                    ->toggleable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->dateTime(static::dateFormat())
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->dateTime(static::dateFormat())
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
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
                ]),
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
