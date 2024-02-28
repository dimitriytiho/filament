<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DummyResource\Pages\CreateDummy;
use App\Filament\Resources\DummyResource\Pages\EditDummy;
use App\Filament\Resources\DummyResource\Pages\ListDummies;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\MenuName;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuNameResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = MenuName::class;
    public static ?string $table = 'menu_names';
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?int $navigationSort = 51;


    /**
     * Не показывать в боковом меню.
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function forms(): array
    {
        return [
            TextInput::make('id')
                ->disabled()
                ->visible((bool) request()->segment(3))
                ->translateLabel(),
            TextInput::make('name')
                ->maxLength(255)
                ->required()
                ->translateLabel(),
            TextInput::make('sort')
                ->integer()
                ->minValue(0)
                ->maxValue(65535)
                ->default(5000)
                ->translateLabel(),
        ];
    }

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
                                TextInput::make('name')
                                    ->maxLength(255)
                                    ->required()
                                    ->translateLabel(),
                                TextInput::make('sort')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(65535)
                                    ->default(5000)
                                    ->translateLabel(),
                            ]),
                    ]),

                Section::make()
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn ($record) => $record === null)
                    ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('id')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
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
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
            ])
            ->filters([
                //
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
            //
        ];
    }*/

    public static function getPages(): array
    {
        return [
            'index' => ListDummies::route('/'),
            'create' => CreateDummy::route('/create'),
            'edit' => EditDummy::route('/{record}/edit'),
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
        return ['name'];
    }*/
}
