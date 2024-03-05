<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteHelper
{
    /**
     * Возвращается маршрут, если он есть, иначе ссылка на главную.
     *
     * @param string|null $routeName - название маршрута.
     * @param string|null $parameter - параметр в маршруте, необязательный параметр (если передаваемый параметр не существует, то маршрут всё равно будет возвращён).
     * @return string|null
     */
    public static function hasRoute(?string $routeName, string $parameter = null): ?string
    {
        if (Route::has($routeName)) {
            return $parameter ? route($routeName, $parameter) : route($routeName);
        }
        return null;
    }

    /**
     * Проверяет, содержит ли маршрут указанную строку.
     *
     * @param string $str
     * @return bool
     */
    public static function is(string $str): bool
    {
        return Str::contains(request()->path(), $str);
    }

    /**
     * Возвращает маршрут для ресурсов Filament.
     *
     * @param string $resource
     * @param string|int|null $id
     * @param string|null $action
     * @return string|null
     */
    public static function getFilamentRoute(string $resource, string|int $id = null,string $action = null): ?string
    {
        if (!$action) {
            $action = $id ? 'edit' : 'index';
        }
        $routeName = 'filament.' . config('filament.slug') . '.resources.' . $resource . '.' . $action;
        if (Route::has($routeName)) {
            return route($routeName, $id);
        }
        return null;
    }
}
