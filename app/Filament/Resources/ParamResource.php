<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParamResource\Pages\{CreateParam, EditParam, ListParams};
use Filament\Forms\Components\{Grid, KeyValue, Placeholder, Section, TextInput, Textarea};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Form;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Param;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ParamResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Param::class;
    public static ?string $table = 'params';
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?int $navigationSort = 95;

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
                                TextInput::make('key')
                                    ->maxLength(40)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->translateLabel(),
                                Textarea::make('value')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                KeyValue::make('data')
                                    ->reorderable()
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
                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('value')
                    ->tooltip(fn ($record): ?string => $record->value)
                    ->limit(40)
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->toggleable()
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
            'index' => ListParams::route('/'),
            'create' => CreateParam::route('/create'),
            'edit' => EditParam::route('/{record}/edit'),
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
    public static function getGloballySearchableAttributes(): array
    {
        return ['key'];
    }
}
