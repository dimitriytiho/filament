<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UrlHelper
{
    private const SESSION_PREFIX = 'prev_url';

    /**
     * @param string $slugInUrl
     * @return void
     */
    public static function set(string $slugInUrl): void
    {
        $prev = url()->previous();
        if (Str::is("*{$slugInUrl}*", $prev)) {
            session()->put(self::SESSION_PREFIX . '.' . $slugInUrl, $prev);
        }
    }

    /**
     * @param string $slugInUrl
     * @return string|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(string $slugInUrl): ?string
    {
        return session()->get(self::SESSION_PREFIX . '.' . $slugInUrl);
    }

    /**
     * @return array|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function all(): ?array
    {
        return session()->get(self::SESSION_PREFIX);
    }


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
