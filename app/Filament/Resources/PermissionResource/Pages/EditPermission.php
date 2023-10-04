<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->url($this->getCreateUrlFromEditPage())
                ->translateLabel(),
            Actions\DeleteAction::make(),
        ];
    }
}
