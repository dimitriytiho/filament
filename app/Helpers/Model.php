<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Model
{
    /**
     *
     * @param string $tableName
     * @return bool
     *
     * Проверяет существует ли таблица в БД с кэшированием.
     */
    public static function hasTable(string $tableName): bool
    {
        return cache()->rememberForever('has_table_' . $tableName, fn() => Schema::hasTable($tableName));
    }

    /**
     * @return array
     *
     * Получить все модели из папки app/Models c пространством имён.
     */
    public static function allModels(): array
    {
        $res = [];
        $path = 'App\Models\\';
        $files = File::files(app_path('Models'));
        foreach ($files as $file) {
            $res[] = $path . $file->getBasename('.php');
        }
        return $res;
    }
}
