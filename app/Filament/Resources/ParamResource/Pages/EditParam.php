<?php

namespace App\Filament\Resources\ParamResource\Pages;

use App\Filament\Resources\ParamResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditParam extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = ParamResource::class;

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
