<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\{DeleteAction, EditAction, ForceDeleteAction, RestoreAction};

class EditPermission extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
            Action::make('create')
                ->url(FilamentHelper::getUrl(self::getTable(), 'create'))
                ->outlined()
                ->translateLabel(),
            /*EditAction::make()
                ->label('Save')
                ->using(function ($record) {
                    $record->update($this->data);
                    return $record;
                })
                ->translateLabel(),*/
        ];
    }
}
