<?php

namespace App\Helpers;

use App\Models\Param;
use Illuminate\Support\Collection;

/**
 * В видах можно вызывать без пространства имён.
 */
class ParamHelper
{
    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        $cacheTime = 604800; // 1 Неделя
        return cache()->remember('params_data', $cacheTime, fn () => Param::select(['key', 'value', 'data'])->get()->keyBy('key'));
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
