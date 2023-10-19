<?php

namespace App\Filament\Resources\DummyResource\Pages;

use App\Filament\Resources\DummyResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDummy extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = DummyResource::class;
}
