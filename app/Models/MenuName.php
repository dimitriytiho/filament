<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuName extends Model
{
    use HasFactory, SoftDeletes;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    /**
     * Связь один к многим.
     * @return HasMany
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

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
