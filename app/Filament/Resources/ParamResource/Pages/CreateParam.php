<?php

namespace App\Filament\Resources\ParamResource\Pages;

use App\Filament\Resources\ParamResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;

class CreateParam extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = ParamResource::class;
}
