<?php

namespace App\Models;

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
}
