<?php

namespace App\Helpers;

use App\Models\Param;
use Illuminate\Support\Collection;

/**
 * В видах можно вызывать без пространства имён, например Param::get('name').
 */
class ParamHelper
{
    /**
     * Все настройки.
     * @return Collection
     */
    public static function all(): Collection
    {
        $cacheTime = 604800; // 1 Неделя
        return cache()->remember('params_al', $cacheTime, fn () => Param::select(['id', 'key', 'value', 'data'])->get());
    }

    /**
     * Получить значение настройки по имени.
     *
     * @param string|int|null $key
     * @return string|null
     */
    public static function get(string|int|null $key): ?string
    {
        return self::all()?->keyBy('key')?->get($key)?->value;
    }

    /**
     * Получить значение настройки по id.
     *
     * @param string|int|null $id
     * @return string|null
     */
    public static function find(string|int|null $id): ?string
    {
        return self::all()?->find($id)?->value;
    }

    /**
     * Получить data настройки по имени.
     *
     * @param string|int|null $key
     * @return array
     */
    public static function data(string|int|null $key): array
    {
        return self::all()?->keyBy('key')?->get($key)?->data ?: [];
    }

    /**
     * Получить data настройки по id.
     *
     * @param string|int|null $id
     * @return array
     */
    public static function findData(string|int|null $id): array
    {
        return self::all()?->find($id)?->data ?: [];
    }
}
