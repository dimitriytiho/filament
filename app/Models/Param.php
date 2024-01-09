<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    use HasFactory;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    protected static function boot(): void
    {
        parent::boot();


        // При создании элемента
        static::creating(function (self $model) {

            // Удалить кэш
            cache()->flush();
        });

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
