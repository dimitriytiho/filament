<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        //$menuName = MenuName::orderBy('sort')->pluck('name', 'id');
        return [
            /*SelectAction::make('menuName')
                ->options($menuName)
                ->action(function (): void {

                    //

                    Notification::make()
                        ->title(__('completed_successfully'))
                        ->success()
                        ->send();
                }),*/
            CreateAction::make()
                ->outlined()
                ->translateLabel(),
        ];
    }
}
