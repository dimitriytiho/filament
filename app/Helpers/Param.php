<?php

namespace App\Helpers;

use App\Models\Param as ParamModel;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        $time = config('filament.cache_time') ?: 3600;
        return cache()->remember('params', $time, function () {
            return ParamModel::select(['key', 'value', 'data'])->get()->keyBy('key');
        });
    }

    /**
     * @param string|int|null $key
     * @return string|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(string|int|null $key): ?string
    {
        return app('registry')->get('params')[$key]?->value ?? null;
    }

    /**
     * @param string|int|null $key
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function data(string|int|null $key): array
    {
        $keyName = 'item';
        $res = [];
        $data = app('registry')->get('params')[$key]?->data ?? null;
        if ($data) {
            foreach ($data as $val) {
                $res[] = $val[$keyName] ?? null;
            }
        }
        return $res;
    }
}
