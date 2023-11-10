<?php

namespace App\Filament\Traits;

use Illuminate\Contracts\Support\Htmlable;

trait ResourceActionTrait
{
    public function getRecordTitle(): Htmlable|string
    {
        return '';
    }

    public function getTitle(): Htmlable|string
    {
        $action = intval(request()?->segment(3)) ? 'edit' : 'create';
        return __("filament-panels::resources/pages/{$action}-record.title", ['label' => $this->getRecordTitle()]);
    }

    public static function getTable(): string
    {
        return self::$resource::$table;
    }
}
