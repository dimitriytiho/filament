<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\{CreateUser, EditUser, ListUsers};
use Filament\Forms\Components\{Grid, Placeholder, Section, Select, TextInput};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Form;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\User;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\{SelectFilter, TrashedFilter};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = User::class;
    public static ?string $table = 'users';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 91;


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

    /**
     * В левом меню показывать кол-во элементов в таблице.
     *
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return cache()->remember(self::$table . '_count', FilamentHelper::cacheTime(), fn (): int => static::$model::count());
    }

    /**
     * Колонки при создании и редактировании.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        $gravatar = new HtmlString(__('avatar_description') . '<a href="https://www.gravatar.com" class="text-primary-600" target="_blank">Gravatar</a>');
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
                                TextInput::make('email')
                                    ->maxLength(255)
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->translateLabel(),
                                Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(RoleResource::forms())
                                    ->createOptionAction(fn ($action) => $action->label(__('Create')))
                                    ->translateLabel(),
                                Select::make('permissions')
                                    ->relationship('permissions', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(PermissionResource::forms())
                                    ->createOptionAction(fn ($action) => $action->label(__('Create')))
                                    ->translateLabel(),
                                TextInput::make('password')
                                    ->maxLength(50)
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->same('password_confirmation')
                                    ->confirmed()
                                    ->minLength(8)
                                    ->translateLabel(),
                                TextInput::make('password_confirmation')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->translateLabel(),
                            ]),
                    ]),

                Section::make()
                    ->description(fn ($record) => $record?->id === auth()?->user()?->id ? $gravatar : null)
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
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('email')
                    ->color('gray')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('permissions.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
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
                TextColumn::make('deleted_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name', fn (Builder $query) => $query->orderBy('id', 'desc'))
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                SelectFilter::make('permissions')
                    ->relationship('permissions', 'name', fn (Builder $query) => $query->orderBy('id', 'desc'))
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                TrashedFilter::make(),
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

    /**
     * Колонки для модального окна.
     *
     * @param Infolist $infolist
     * @return Infolist
     */
    /*public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id')
                ->translateLabel(),
                TextEntry::make('name')
                ->translateLabel(),
                TextEntry::make('email')
                ->translateLabel(),
                TextEntry::make('roles.name')
                ->translateLabel(),
                TextEntry::make('permissions.name')
                ->translateLabel(),
                TextEntry::make('updated_at')
                ->translateLabel()
                ->dateTime(self::dateFormat()),
                TextEntry::make('created_at')
                ->translateLabel()
                ->dateTime(self::dateFormat()),
            ])
            ->columns(1)
            ->inlineLabel();
    }*/

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
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
        return ['name'];
    }
}
