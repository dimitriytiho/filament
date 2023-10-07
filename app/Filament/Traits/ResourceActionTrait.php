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

    public static function getCreateUrl(): string
    {
        return route('filament.' . self::getSlug() . '.resources.' . self::getTable() . '.create');
    }

    public static function getTable(): string
    {
        return self::$resource::$table;
    }

    public static function getSlug(): string
    {
        return config('filament.slug');
    }
}
