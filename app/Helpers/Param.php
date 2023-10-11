<?php

namespace App\Helpers;

use App\Models\Param as ParamModel;
use Illuminate\Support\Collection;

/**
 * В видах можно вызывать без пространства имён.
 */
class Param
{
    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        $cacheTime = config('filament.cache_time') ?: 600;
        return cache()->remember('params_data', $cacheTime, fn () => ParamModel::select(['key', 'value', 'data'])->get()->keyBy('key'));
    }

    /**
     * @param string|int|null $key
     * @return string|null
     */
    public static function get(string|int|null $key): ?string
    {
        return self::all()?->get($key)?->value;
    }

    /**
     * @param string|int|null $key
     * @return array
     */
    public static function data(string|int|null $key): array
    {
        return self::all()?->get($key)?->data ?: [];
    }
}
