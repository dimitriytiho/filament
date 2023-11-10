<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DummyResource\Pages;
use App\Filament\Resources\DummyResource\RelationManagers;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Dummy;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DummyResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Dummy::class;
    public static ?string $table = 'dummies';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
        return $form
            ->columns(3)
            ->schema([

                Section::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([

                        Grid::make()
                            ->schema([
                                TextInput::make('title')
                                    ->maxLength(255)
                                    //->live(debounce: 1000)
                                    ->suffixAction(
                                        Action::make('create')
                                            //->disabled($form->getOperation() === 'create')
                                            ->label(__('turn_into_link'))
                                            ->icon('heroicon-s-arrows-right-left')
                                            ->action(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    //->url('/admin/files/create')
                                    //->openUrlInNewTab()
                                    )
                                    //->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required()
                                    ->translateLabel(),
                                TextInput::make('slug')
                                    ->default(fn(Get $get): string => Str::slug($get('title')))
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->required()
                                    ->translateLabel(),
                            ]),
                        Grid::make()
                            ->schema([
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
                        Toggle::make('active')
                            ->default(false)
                            ->translateLabel(),
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

                Section::make()
                    ->columnSpan(['lg' => 3])
                    ->schema([
                        MarkdownEditor::make('body')
                            ->toolbarButtons()
                            ->translateLabel(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            /*->query(function (User $model) {
                return $model->where('user_id', filament()?->auth()?->user()?->getAuthIdentifier());
            })*/
            ->poll('60s')
            ->columns([
                TextColumn::make('id')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('slug')
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('sort')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->translateLabel(),
                IconColumn::make('active')
                    ->boolean()
                    ->toggleable()
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
                TernaryFilter::make('active')
                    ->placeholder(__('all'))
                    ->translateLabel(),
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
