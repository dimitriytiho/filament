<?php

namespace App\Helpers;

class ArrHelper
{
    /**
     * Возвращает массив в который добавляет значения из ключей.
     *
     * @param array $arr
     * @return array
     */
    public static function valInKey(array $arr): array
    {
        $res = [];
        foreach ($arr as $val) {
            $res[$val] = $val;
        }
        return $res;
    }
}
