<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DummyResource\Pages\{CreateDummy, EditDummy, ListDummies};
use Filament\Forms\Components\{Grid, Placeholder, Section, Select, TextInput};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Form;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Role;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                            ->schema(self::forms()),
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
                TextColumn::make('permissions.name')
                    ->badge()
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
}
