<?php

namespace App\Helpers;

class TreeHelper
{
    private const KEY = 'children';
    private const VAL = 'title';
    private const TAB = '-';

    /**
     * @param array $arr
     * @param string $column
     * @return array
     */
    public static function build(array $arr, string $column = 'parent_id'): array
    {
        $tree = [];
        foreach ($arr as $id => &$node) {
            if (empty($node[$column])) {
                $tree[$id] = &$node;
            } else {
                $arr[$node[$column]][self::KEY][$id] = &$node;
            }
        }
        return $tree;
    }

    /**
     * Выключить или включить всех потомков, в зависимости от статуса родителя.
     * Модель должна иметь колонку active!
     *
     * @param object|null $model
     * @return void
     */
    public static function descendantsActive(?object $model): void
    {
        if ($model) {
            $active = (bool) $model->active;
            $model->descendants->each(function ($item) use ($active) {
                if (!$active === (bool) $item->active) {
                    $item->active = $active;
                    $item->save();
                }
            });
        }
    }

    /**
     * Return tree for select
     *
     * @param array $tree
     * @return array
     */
    public static function treeForSelect(array $tree): array
    {
        return self::selectTreeLoop($tree);
    }
    private static function selectTreeLoop(array $tree, string $tab = '', array $options = []): array
    {
        foreach ($tree as $item) {
            $id = $item['id'] ?? null;
            $children = $item[self::KEY] ?? [];
            $val = $item[self::VAL] ?? null;
            $options[(string) $id] = $tab . $id . ' ' . $val;
            if ($children) {
                $optionsChildren = self::selectTreeLoop($children, $tab . self::TAB, $options);
                $options += array_diff($optionsChildren, $options);
            }
        }
        return $options;
    }
}
