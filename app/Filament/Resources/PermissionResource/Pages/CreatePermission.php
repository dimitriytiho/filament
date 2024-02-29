<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = PermissionResource::class;
}
