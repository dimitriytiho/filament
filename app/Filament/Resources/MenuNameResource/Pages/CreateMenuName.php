<?php

namespace App\Filament\Resources\MenuNameResource\Pages;

use App\Filament\Resources\MenuNameResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuName extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = MenuNameResource::class;
}
