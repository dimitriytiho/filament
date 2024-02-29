<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Param extends Model
{
    use HasFactory, SoftDeletes;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // При сохранении элемента
        static::saving(function (self $model) {

            // Удалить кэш
            cache()->flush();
        });

        // При удалении элемента
        static::deleting(function (self $model) {

            // Удалить кэш
            cache()->flush();
        });
    }
}
