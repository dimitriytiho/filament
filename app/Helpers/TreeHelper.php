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
     * Return tree for select
     *
     * @param array $tree
     * @return array
     */
    public static function select(array $tree): array
    {
        return self::selectTree($tree);
    }
    private static function selectTree(array $tree, string $tab = '', array $options = []): array
    {
        foreach ($tree as $item) {
            $id = $item['id'] ?? null;
            $children = $item[self::KEY] ?? [];
            $val = $item[self::VAL] ?? null;
            $options[(string) $id] = $tab . $val . ' ' . $id;
            if ($children) {
                $optionsChildren = self::selectTree($children, $tab . self::TAB, $options);
                $options += array_diff($optionsChildren, $options);
            }
        }
        return $options;
    }
}
