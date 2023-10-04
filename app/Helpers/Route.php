<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;

class Route
{
    /**
     *
     * @param string|null $routeName - название маршрута.
     * @param string|null $parameter - параметр в маршруте, необязательный параметр (если передаваемый параметр не существует, то маршрут всё равно будет возвращён).
     * @return string|null Возвращается маршрут, если он есть, иначе ссылка на главную.
     *
     * Возвращается маршрут, если он есть, иначе ссылка на главную.
     */
    public static function hasRoute(?string $routeName, string $parameter = null): ?string
    {
        if (RouteFacade::has($routeName)) {
            return $parameter ? route($routeName, $parameter) : route($routeName);
        }
        return null;
    }

    /**
     * @param string $str
     * @return bool
     */
    public static function is(string $str): bool
    {
        return Str::contains(request()->path(), $str);
    }

    /**
     * @param string|null $domain
     * @return string|false
     */
    public static function clearDomain(string|null $domain): string|false
    {
        $domain = str_replace(['https://', 'http://'], '', $domain);
        $domain = strtok($domain, '/');
        return strtok($domain, '?');
    }
}
