<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, SoftDeletes};

class Form extends Model
{
    use SoftDeletes;

    /**
     * Запрещается редактировать.
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Скрыть из модели.
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * @return belongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $model) {

            // Удалить кэш
            cache()->flush();
        });

        static::deleting(function ($model) {

            // При мягком удалении не сработает
            //if ($model->isForceDeleting()) {}

            // Удалить кэш
            cache()->flush();
        });
    }
}
