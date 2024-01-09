<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return void
     */
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

            // Удаление связанного элемента
            $model->permissions()->delete();

            // Удалить кэш
            cache()->flush();
        });
    }
}
