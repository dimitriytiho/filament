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
}
