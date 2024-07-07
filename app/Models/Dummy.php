<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dummy extends Model
{
    use HasFactory, SoftDeletes;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'active' => 'bool',
        'sort' => 'int',
        'body' => 'string',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /*protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'options' => \Illuminate\Database\Eloquent\Casts\AsEnumCollection::of(\App\Enums\UserOption::class),
        ];
    }*/

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // При создании элемента
//        static::creating(function (self $model) {
//
//        });

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
