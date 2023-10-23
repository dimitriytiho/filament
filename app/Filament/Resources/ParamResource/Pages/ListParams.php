<?php

namespace App\Filament\Resources\ParamResource\Pages;

use App\Filament\Resources\ParamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParams extends ListRecords
{
    protected static string $resource = ParamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->outlined()
                ->translateLabel(),
        ];
    }
}
