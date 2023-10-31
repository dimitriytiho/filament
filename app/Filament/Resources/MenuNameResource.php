<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuNameResource\Pages;
use App\Filament\Resources\MenuNameResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\MenuName;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
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
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuNames::route('/'),
            'create' => Pages\CreateMenuName::route('/create'),
            'edit' => Pages\EditMenuName::route('/{record}/edit'),
        ];
    }
}
