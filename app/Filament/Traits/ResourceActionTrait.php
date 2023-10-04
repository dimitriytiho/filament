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
        $action = app('registry')?->get('requestAction') ?: 'edit';
        return $action ? __("filament-panels::resources/pages/{$action}-record.title", ['label' => $this->getRecordTitle()]) : '';
    }

    protected function getCreateUrlFromEditPage(): string
    {
        return route(str_replace('edit', 'create', request()->route()->getName()));
    }
}
