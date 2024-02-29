<?php

namespace App\Filament\Resources\MenuNameResource\Pages;

use App\Filament\Resources\MenuNameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuNames extends ListRecords
{
    protected static string $resource = MenuNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->outlined()
                ->translateLabel(),
        ];
    }
}
