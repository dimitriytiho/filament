<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;

class CreateMenu extends CreateRecord
{
    use ResourceActionTrait;

    protected static string $resource = MenuResource::class;

    /**
     * @param array $data
     * @return array|mixed[]
     * @throws Halt
     */
    /*protected function mutateFormDataBeforeCreate(array $data): array
    {
//        Notification::make()
//            ->danger()
//            ->title('Error')
//            ->body('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis cupiditate dignissimos eaque error laudantium voluptatibus.')
//            ->persistent()
//            ->send();
//        $this->halt();

        return $data;
    }*/
}
