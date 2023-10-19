<?php

namespace App\Filament\Resources\DummyResource\Pages;

use App\Filament\Resources\DummyResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditDummy extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = DummyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('create')
                ->url(FilamentHelper::getUrl(self::getTable(), 'create'))
                ->outlined()
                ->translateLabel(),
        ];
    }
}
