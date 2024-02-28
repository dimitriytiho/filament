<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DummyResource\Pages\{CreateDummy, EditDummy, ListDummies};
use Filament\Forms\Components\{Grid, KeyValue, MarkdownEditor, Placeholder, Section, Select, TextInput, Toggle};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\{Form, Get, Set};
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\Dummy;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DummyResource extends Resource
{
    use ResourceTrait;

    protected static ?string $model = Dummy::class;
    public static ?string $table = 'dummies';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 50;

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
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'codeBlock',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'table',
                                'undo',
                            ])
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
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('slug')
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('sort')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable()
                    ->translateLabel(),
                IconColumn::make('active')
                    ->boolean()
                    ->toggleable()
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
