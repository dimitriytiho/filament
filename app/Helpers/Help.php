<?php

namespace App\Helpers;

/**
 * В видах можно вызывать без пространства имён.
 */
class Help
{
    /**
     * Распечатывает массив или объект.
     *
     * @param mixed $data - массив для распечатки.
     * @param bool|int $die - передать true, если надо остановить скрипт.
     * @return void
     */
    public static function debug(mixed $data, bool|int $die = false): void
    {
        echo '<pre>' . PHP_EOL . print_r($data, true) . PHP_EOL . '</pre>';
        if ($die) {
            die;
        }
    }
}
