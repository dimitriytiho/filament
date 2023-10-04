<?php

/*
 * Здесь дополнительные методы, что добавить в Eloquent ORM новые команды.
 * Для использования наследовать этот trait в модели.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ModelTrait
{
    /**
     * Удаляет переданные колонки из модели.
     * Использование ->deleteColumns(['id'])
     */
    public function scopeDeleteColumns($query, array $columns)
    {
        return $query->select(array_diff((new static)->getColumns(), $columns));
    }

    /**
     * Возвращает колонки модели.
     * Использование ->getColumns()
     */
    public static function getColumns(): array
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    /**
     * Проверить в scope: сейчас попадает ли в промежуток времени.
     * Использование ->betweenTime()
     */
    public function scopeBetweenTime($query)
    {
        return $query
            ->where('start', '<', now())
            ->where('end', '>', now());
    }


    /**
     * @return array
     * Возвращает колонки данной модели.
     */
    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
