<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Filament\Resources\FormResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Form as FormModel;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class FormResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = FormModel::class;
    public static ?string $table = 'forms';
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?int $navigationSort = 10;


    /**
     * В левом меню показывать кол-во элементов в таблице.
     *
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return cache()->remember(static::$table . '_count', FilamentHelper::cacheTime(), fn (): int => static::$model::count());
    }

    public static function form(Form $form): Form
    {
        $userId = $form->getRecord()->user_id;
        return $form
            ->columns(3)
            ->schema([

                Section::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Placeholder::make('user')
                            ->content(new HtmlString(FilamentHelper::getLink('users', 'edit', $userId, 'name')))
                            ->translateLabel(),
                        MarkdownEditor::make('message')
                            ->required()
                            ->toolbarButtons()
                            ->translateLabel(),
                    ]),

                Section::make()
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn ($record) => $record === null)
                    ->schema([
                        Placeholder::make('id')
                            ->content(fn ($record): ?int => $record?->id)
                            ->translateLabel(),
                        Placeholder::make('created_at')
                            ->hidden(fn ($record) => $record === null)
                            ->content(fn ($record): ?string => $record?->created_at?->format(FilamentHelper::dateFormat()))
                            ->translateLabel(),
                        Placeholder::make('updated_at')
                            ->hidden(fn ($record) => $record === null)
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
                TextColumn::make('user.id')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString(FilamentHelper::getLink('users', 'edit', $state, 'name')))
                    ->translateLabel(),
                TextColumn::make('message')
                    ->limit(40)
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->dateTime(FilamentHelper::dateFormat())
                    ->badge()
                    ->color('gray')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
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
                TrashedFilter::make(),
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
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
