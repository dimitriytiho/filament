<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\{DeleteAction, EditAction, ForceDeleteAction, RestoreAction};

class EditUser extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = UserResource::class;


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
            Action::make('create')
                ->url(FilamentHelper::getUrl(self::getTable(), 'create'))
                ->outlined()
                ->translateLabel(),
            /*EditAction::make()
                ->label('Save')
                ->using(function ($record) {
                    $data = $this->data;
                    // Unset password
                    if (key_exists('password', $data) && empty($data['password'])) {
                        unset($data['password']);
                    }
                    $record->update($data);
                    return $record;
                })
                ->translateLabel(),*/
        ];
    }

    // Runs after the form fields are saved to the database.
    /*protected function afterSave(): void
    {
        //dump($this->data ?? null);
    }*/
}
