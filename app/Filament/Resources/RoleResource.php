<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class RoleResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Role::class;
    public static ?string $table = 'roles';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 92;


    /**
     * Доступ к данному ресурсу ресурс.
     *
     * @param string $action
     * @param Model|null $record
     * @return bool
     */
    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()?->user()?->isAdmin();
    }

    /**
     * Группа в левом меню.
     *
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return __('management');
    }

    public static function forms(): array
    {
        return [
            TextInput::make('id')
                ->disabled()
                ->translateLabel(),
            TextInput::make('name')
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->required()
                ->translateLabel(),
            Select::make('permissions')
                ->relationship('permissions', 'name')
                ->multiple()
                ->searchable()
                ->preload()
                ->createOptionForm(PermissionResource::forms())
                ->translateLabel(),
        ];
    }

    /**
     * Колонки при создании и редактировании.
     *
     * @param Form $form
     * @return Form
     */
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
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->translateLabel(),
                                Select::make('permissions')
                                    ->relationship('permissions', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(PermissionResource::forms())
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

    /**
     * Таблица при листинге.
     *
     * @param Table $table
     * @return Table
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        $dateFormat = FilamentHelper::dateFormat();
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
                TextColumn::make('permissions.name')
                    ->sortable(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
