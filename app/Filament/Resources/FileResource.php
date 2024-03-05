<?php

namespace App\Filament\Resources;

use App\Helpers\FileHelper;
use App\Filament\Resources\FileResource\Pages\{CreateFile, EditFile, ListFiles};
use Filament\Forms\Components\{Grid, Placeholder, Section, FileUpload, TextInput, Toggle};
use Filament\Tables\Actions\{ActionGroup, BulkActionGroup, CreateAction, DeleteAction, DeleteBulkAction, EditAction, ForceDeleteBulkAction, RestoreBulkAction};
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Filament\Forms\Form;
use App\Filament\Traits\ResourceTrait;
use App\Helpers\FilamentHelper;
use App\Models\File;
use Filament\Resources\Resource;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FileResource extends Resource
{
    use ResourceTrait;

    public static ?string $model = File::class;
    public static ?string $table = 'files';
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?int $navigationSort = 80;

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
                                TextInput::make('name')
                                    ->requiredWithout('name,mine_type')
                                    ->maxLength(255)
                                    ->translateLabel(),
                                TextInput::make('sort')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(65535)
                                    ->default(5000)
                                    ->translateLabel(),
                                TextInput::make('mime_type')
                                    ->requiredWithout('mime_type,name')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->required()
                                    ->translateLabel(),
                                TextInput::make('size')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->required()
                                    ->translateLabel(),
                                FileUpload::make('file')
                                    ->disk('http')
                                    ->directory('file/' . date('m-Y'))
                                    ->openable()
                                    ->required()
                                    ->maxSize(config('filament.file.size')) // Указать в кб
                                    ->acceptedFileTypes(config('filament.file.mine_type')) // Указать разрешаемую миме-типы
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                                    ->hint(__('max') . FileHelper::getBigSize(config('filament.file.size')) . __('mb') . ', ' . implode(', ', config('filament.file.mine_type')))
                                    ->translateLabel(),
                                TextInput::make('ext')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->required()
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
                TextColumn::make('file')
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->copyable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('ext')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('mime_type')
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('size')
                    ->label(__('Size') . __('kb'))
                    ->formatStateUsing(fn (int $state): string => FileHelper::getBigSize($state))
                    ->color('gray')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap()
                    ->toggleable(),
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
                //CreateAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFiles::route('/'),
            //'create' => CreateFile::route('/create'),
            'edit' => EditFile::route('/{record}/edit'),
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
