<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DummyResource\Pages;
use App\Filament\Resources\DummyResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DummyResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = User::class;
    public static ?string $table = 'users';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 10;

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
        $dateFormat = FilamentHelper::dateFormat();
        return $table
            ->query(function (Feed $model) {
                return $model->where('user_id', filament()?->auth()?->user()?->getAuthIdentifier());
            })
            ->poll('60s')
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->dateTime($dateFormat)
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->dateTime($dateFormat)
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('deleted_at')
                    ->dateTime($dateFormat)
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
            ])
            ->filters([
                TrashedFilter::make(),
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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDummies::route('/'),
            'create' => Pages\CreateDummy::route('/create'),
            'edit' => Pages\EditDummy::route('/{record}/edit'),
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
        return ['title'];
    }
}
