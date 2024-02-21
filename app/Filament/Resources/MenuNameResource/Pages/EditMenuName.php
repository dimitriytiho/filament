<?php

namespace App\Filament\Resources\MenuNameResource\Pages;

use App\Filament\Resources\MenuNameResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuName extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = MenuNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('create')
                ->url(FilamentHelper::getUrl(self::getTable(), 'create'))
                ->outlined()
                ->translateLabel(),
            EditAction::make()
                ->label('Save')
                ->using(function ($record, array $data) {
                    $record->update($data);
                    return $record;
                })
                ->translateLabel(),
        ];
    }
}
