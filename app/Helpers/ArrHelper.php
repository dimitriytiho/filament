<?php

namespace App\Helpers;

class ArrHelper
{
    /**
     * Возвращает массив в который добавляет в ключи значения.
     *
     * @param array $arr
     * @param bool $translate
     * @return array
     */
    public static function valInKey(array $arr, bool $translate = false): array
    {
        $res = [];
        foreach ($arr as $val) {
            $res[$val] = $translate ? __($val) : $val;
        }
        return $res;
    }
}
