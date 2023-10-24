<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * @param string|null $url
     * @return string|false
     */
    public static function clearDomain(string|null $url): string|false
    {
        $url = self::clearQueryParams($url);
        return self::clearProtocol($url);
    }

    /**
     * @param string|null $url
     * @return string|false
     */
    public static function clearProtocol(string|null $url): string|false
    {
        return str_replace(['https://', 'http://'], '', $url);
    }

    /**
     * @param string|null $url
     * @return string|false
     */
    public static function clearQueryParams(string|null $url): string|false
    {
        return strtok($url, '?');
    }
}
