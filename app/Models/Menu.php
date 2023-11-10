<?php

namespace App\Models;

use App\Helpers\TreeHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory, NodeTrait;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'attrs' => 'json',
    ];


    /**
     * Связь один ко многим внутри модели.
     * @return BelongsTo
     */
    public function menus(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * Связь обратная многие к одному.
     * @return BelongsTo
     */
    public function menuName(): BelongsTo
    {
        return $this->belongsTo(MenuName::class);
    }

    protected static function boot(): void
    {
        parent::boot();


        // При создании элемента
        static::creating(function ($model) {

            // Выключить или включить всех потомков, в зависимости от статуса родителя.
            TreeHelper::descendantsActive($model);

            // Удалить кэш
            cache()->flush();
        });


        // При сохранении элемента
        static::saving(function ($model) {

            // Выключить или включить всех потомков, в зависимости от статуса родителя.
            TreeHelper::descendantsActive($model);

            // Удалить кэш
            cache()->flush();
        });


        // При удалении элемента
        static::deleting(function ($model) {
            // Удалить кэш
            cache()->flush();
        });
    }
}
