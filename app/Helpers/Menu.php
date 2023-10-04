<?php

namespace App\Helpers;

use App\Models\MenuName;

/**
 * В видах можно вызывать без пространства имён.
 */
class Menu
{
    /**
     * @param string|int|null $name
     * @param bool $active
     * @return array
     */
    public static function get(string|int|null $name, bool $active = true): array
    {
        return MenuName::where('name', $name)
            ->with(['menus' => function ($query) use ($active) {
                if ($active) {
                    $query->where('active', '1');
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
     * @return array
     */
    public static function find(string|int|null $id, bool $active = true): ?array
    {
        return MenuName::where('id', $id)
            ->with(['menus' => function ($query) use ($active) {
                if ($active) {
                    $query->where('active', '1');
                }
                $query->orderBy('sort', 'desc');
            }])
            ->first()
            ?->menus
            ?->toTree()
            ?->toArray() ?: [];
    }
}
