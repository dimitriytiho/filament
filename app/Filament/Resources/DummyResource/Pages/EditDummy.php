<?php

namespace App\Filament\Resources\DummyResource\Pages;

use App\Filament\Resources\DummyResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\EditAction;

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
            EditAction::make()
                ->label('Save')
                ->using(function ($record, array $data) {
                    $record->update($data);
                    return $record;
                })
                ->translateLabel(),
        ];
    }

    // Runs before the form fields are saved to the database.
    protected function beforeSave(): void
    {
        //dd($this->getRecord()->slug);
//        \Filament\Notifications\Notification::make()
//            ->title('test')
//            ->danger()
//            ->send();
//        $this->halt();
    }
}
