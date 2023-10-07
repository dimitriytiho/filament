<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParamResource\Pages;
use App\Filament\Resources\ParamResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Models\Param;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('id')
                            ->disabled()
                            ->translateLabel(),
                        TextInput::make('key')
                            ->maxLength(40)
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->translateLabel(),
                        TextInput::make('value')
                            ->maxLength(255)
                            ->translateLabel(),
                        Repeater::make('data')
                            ->schema([
                                TextInput::make('item')
                                    ->translateLabel(),
                            ])
                            ->translateLabel(),
                    ])
                    ->columns(2),
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
                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('value')
                    ->translateLabel()
                    ->limit(40),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParams::route('/'),
            'create' => Pages\CreateParam::route('/create'),
            'edit' => Pages\EditParam::route('/{record}/edit'),
        ];
    }
}
