<?php

namespace App\Filament\Resources\MenuNameResource\Pages;

use App\Filament\Resources\MenuNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuNames extends ListRecords
{
    protected static string $resource = MenuNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->outlined()
                ->translateLabel(),
        ];
    }
}
