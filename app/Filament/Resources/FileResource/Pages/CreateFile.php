<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = FileResource::class;


    /**
     * https://filamentphp.com/docs/3.x/actions/prebuilt-actions/import#lifecycle-hooks
     * @return void
     */
    /*protected function beforeValidate(): void
    {

    }*/
}
