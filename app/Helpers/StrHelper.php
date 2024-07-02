<?php

namespace App\Helpers;

class StrHelper
{
    /**
     * Склонение слов в зависимости от кол-ва.
     *
     * @param int $count
     * @param string $declension - делитель |
     * @return string
     */
    public function declension(int $count, string $declension): string
    {
        $res = '';
        $declension = explode('|', $declension);
        if (count($declension) > 2) {
            $count100 = $count % 100;
            switch ($count % 10) {
                case 1:
                    if ($count100 == 11) {
                        $res = $declension[2];
                    } else {
                        $res = $declension[0];
                    }
                    break;
                case 2:
                case 3:
                case 4:
                    if (($count100 > 10) && ($count100 < 20)) {
                        $res = $declension[2];
                    } else {
                        $res = $declension[1];
                    }
                    break;
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 0:
                default:
                    $res = $declension[2];
            }
        }
        return $res;
    }
}
