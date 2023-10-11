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
        static::saving(function ($model) {
            cache()->forget($model->getTable() . '_*');
        });
    }
}
