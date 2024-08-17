<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateForm extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = FormResource::class;
}
