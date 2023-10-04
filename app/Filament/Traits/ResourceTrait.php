<?php

namespace App\Filament\Traits;

use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Lang;

trait ResourceTrait
{
    public static function getPluralModelLabel(): string
    {
        return __(self::$table);
    }

    /*public function getTitle(): Htmlable|string
    {

    }*/

    public static function user(): ?User
    {
        return auth()->check() ? auth()->user() : null;
    }

    public static function dateFormat(): ?string
    {
        return config('filament.date_format');
    }

    public static function cacheTime(): ?string
    {
        return config('filament.cache_time') ?: 3600; // second
    }
}
