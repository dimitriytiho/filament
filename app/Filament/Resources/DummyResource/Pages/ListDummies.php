<?php

namespace App\Filament\Resources\DummyResource\Pages;

use App\Filament\Resources\DummyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDummies extends ListRecords
{
    protected static string $resource = DummyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->outlined()
                ->translateLabel(),
        ];
    }
}
