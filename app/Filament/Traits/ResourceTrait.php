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
}
