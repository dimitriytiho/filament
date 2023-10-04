<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = UserResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->url($this->getCreateUrlFromEditPage())
                ->translateLabel(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }

    // Runs after the form fields are saved to the database.
    protected function afterSave(): void
    {
        //dump($this->data ?? null);
    }
}
