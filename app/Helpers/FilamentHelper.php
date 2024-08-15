<?php

namespace App\Helpers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class FilamentHelper
{
    /**
     * @return string
     */
    public static function getSlug(): string
    {
        return config('filament.slug', 'admin');
    }

    /**
     * @return string
     */
    public static function dateFormat(): string
    {
        return config('filament.date_format', 'd.m.Y H:i');
    }

    /**
     * @return string|int
     */
    public static function cacheTime(): string|int
    {
        return config('filament.cache_time', 600); // seconds
    }

    /**
     * @param string $table
     * @param string|null $action = index, create, edit, default - index
     * @param string|int|null $record id for edit action
     * @return string|null
     */
    public static function getUrl(string $table, string $action = null, string|int $record = null): ?string
    {
        $slug = self::getSlug();
        $action = $action ?: 'index';
        $table = str_replace('_', '-', $table);
        $route = "filament.{$slug}.resources.{$table}.{$action}";
        if (Route::has($route)) {
            if ($action === 'edit' && $record) {
                return route($route, ['record' => $record]);
            }
            return route($route);
        }
        return null;
    }

    /**
     * @param string $table
     * @param string $action = index, create, edit
     * @param string|int $recordId id for edit action
     * @param string $modelTitleColumn column name for title in link
     * @return ?string
     */
    public static function getLink(string $table, string $action, string|int $recordId, string $modelTitleColumn): ?string
    {
        $link = null;
        $model = DB::table($table)->find($recordId);
        if ($model) {
            $link = '<a href="' . self::getUrl($table, $action, $recordId) . '">' . $model->{$modelTitleColumn} . '</a>';
        }
        return $link;
    }
}
