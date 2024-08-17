<?php

/*
 * Здесь дополнительные методы, чтобы добавить в Eloquent ORM новые команды.
 * Для использования наследовать этот trait в модели.
 */

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

trait ModelTrait
{
    /**
     * Удаляет переданные колонки из модели.
     * Использование: ->deleteColumns(['id'])
     *
     * @param array $columns
     * @return object
     */
    public function scopeDeleteColumns($query, array $columns): object
    {
        return $query->select(array_diff((new static)->getColumns(), $columns));
    }

    /**
     * Возвращает колонки модели.
     * Использование: ->getColumns()
     * @return array
     */
    public static function getColumns(): array
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    /**
     * Возвращает элементы из промежутка времени.
     * Использование: ->betweenTime(now()->subDays(3))
     *
     * @param object $query
     * @param Carbon|string $start
     * @param Carbon|string|null $end
     * @param string $column
     * @return object
     */
    public function scopeBetweenTime(object $query, Carbon|string $start, Carbon|string $end = null, string $column = 'created_at'): object
    {
        if (!$end) {
            $end = now();
        }
        return $query
            ->where($column, '>', $start)
            ->where($column, '<', $end);
    }

    /**
     * Возвращает колонки данной модели.
     * @return array
     */
    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
