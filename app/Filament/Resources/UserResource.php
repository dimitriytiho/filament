<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Relations\UserRelation;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Traits\ResourceTrait;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Facades\FilamentView;
use Illuminate\View\View;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationGroup;

class UserResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = User::class;
    public static ?string $table = 'users';
    protected static ?string $navigationIcon = 'heroicon-o-users';
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
        return auth()->user()->isAdmin();
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
        return cache()->remember(static::$table . '_count', static::cacheTime(), fn (): int => static::$model::count());
    }

    /**
     * Колонки при создании и редактировании.
     *
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        // Добавить вид в конце контента
        FilamentView::registerRenderHook(
            'panels::page.end',
            fn (): View => view('filament.user.edit'),
        );

        return $form
            ->schema([
                //Forms\Components\Group::make(),
                Section::make()
                    ->schema([
                        TextInput::make('id')
                            ->disabled()
                            ->translateLabel(),
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
                            ->translateLabel(),
                        Select::make('permissions')
                            ->relationship('permissions', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(PermissionResource::forms())
                            ->translateLabel(),
                        TextInput::make('password')
                            ->maxLength(50)
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password_confirmation')
                            ->confirmed()
                            ->minLength(8)
                            ->translateLabel(),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->translateLabel(),
                    ])
                    ->columns(2),
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
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('email')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('roles.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('permissions.name')
                    ->searchable()
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
                TextColumn::make('deleted_at')
                    ->dateTime(static::dateFormat())
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name', function ($query) {
                        return $query->orderBy('id', 'desc');
                    })
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                SelectFilter::make('permissions')
                    ->relationship('permissions', 'name', function ($query) {
                        return $query->orderBy('id', 'desc');
                    })
                    ->searchable()
                    ->preload()
                    ->translateLabel(),
                TrashedFilter::make(),
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
                ->dateTime(static::dateFormat()),
                TextEntry::make('created_at')
                ->translateLabel()
                ->dateTime(static::dateFormat()),
            ])
            ->columns(1)
            ->inlineLabel();
    }*/

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
