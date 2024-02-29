<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Filament\Traits\ResourceActionTrait;
use App\Helpers\FilamentHelper;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\{DeleteAction, EditAction, ForceDeleteAction, RestoreAction};

class EditMenu extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = MenuResource::class;

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
                    $record->update($this->data);
                    return $record;
                })
                ->translateLabel(),*/
        ];
    }

    /*protected function afterFormValidated(): void
    {
        if ($this?->data) {

            // Нельзя сохранить в $parentId свой id
            $id = $this?->data['id'] ?? null;
            $parentId = $this?->data['parent_id'] ?? null;
            if ($id && $id == $parentId) {
                dd(2);
                $this->data['parent_id'] = null;
                $this->record->update($this->data);
            }
            dd(1);
        }
    }*/
}
