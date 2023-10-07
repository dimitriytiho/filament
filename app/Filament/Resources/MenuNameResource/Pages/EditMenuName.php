<?php

namespace App\Filament\Resources\MenuNameResource\Pages;

use App\Filament\Resources\MenuNameResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Actions\Action;
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
                ->url(self::getCreateUrl())
                ->translateLabel(),
        ];
    }
}
