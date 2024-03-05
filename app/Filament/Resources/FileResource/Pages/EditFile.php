<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Traits\ResourceActionTrait;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\{DeleteAction, ForceDeleteAction, RestoreAction};

class EditFile extends EditRecord
{
    use ResourceActionTrait;

    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
