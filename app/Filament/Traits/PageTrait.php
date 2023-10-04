<?php

namespace App\Filament\Traits;

use Illuminate\Contracts\Support\Htmlable;

trait PageTrait
{
    public static function getNavigationLabel(): string
    {
        return __(self::$title);
    }

    public function getTitle(): string | Htmlable
    {
        return __(self::$title);
    }
}
