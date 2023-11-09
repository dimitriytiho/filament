<?php

namespace App\Helpers;

use App\Models\MenuName;
use App\Models\Menu as MenuModel;

/**
 * В видах можно вызывать без пространства имён.
 */
class MenuHelper
{
    /**
     * @param string|int|null $name
     * @param bool $active
     * @param array $descendantsId - исключаем потомков.
     * @return array
     */
    public static function get(string|int|null $name, bool $active = true, array $descendantsId = []): array
    {
        return MenuName::where('name', $name)
            ->with(['menus' => function ($query) use ($active, $descendantsId) {
                if ($descendantsId) {
                    $query->whereNotIn('id', $descendantsId);
                }
                if ($active) {
                    $query->where('active', true);
                }
                $query->orderBy('sort', 'desc');
            }])
            ->first()
            ?->menus
            ?->toTree()
            //?->keyBy('id')
            ?->toArray() ?: [];
    }

    /**
     * @param string|int|null $id
     * @param bool $active
     * @param array $descendantsId - исключаем потомков.
     * @return array
     */
    public static function find(string|int|null $id, bool $active = true, array $descendantsId = []): array
    {
        return MenuName::where('id', $id)
            ->with(['menus' => function ($query) use ($active, $descendantsId) {
                if ($descendantsId) {
                    $query->whereNotIn('id', $descendantsId);
                }
                if ($active) {
                    $query->where('active', true);
                }
                $query->orderBy('sort', 'desc');
            }])
            ->first()
            ?->menus
            ?->toTree()
            ?->toArray() ?: [];
    }

    /**
     * Возвращает всех потомков, которых нельзя выбрать в качестве родителя.
     *
     * @param MenuModel|null $menu
     * @return array
     */
    public static function descendantsAndSelf(?MenuModel $menu): array
    {
        if ($menu) {
            return $menu->descendantsAndSelf($menu->id)?->pluck('id')?->toArray();
        }
        return [];
    }
}
