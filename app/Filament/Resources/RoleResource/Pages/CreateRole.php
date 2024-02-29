<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = RoleResource::class;
}
