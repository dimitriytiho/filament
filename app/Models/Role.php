<?php

namespace App\Models;

use App\Interfaces\ModelPermissionInterface;
use App\Traits\ModelPermissionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model implements ModelPermissionInterface
{
    use HasFactory, SoftDeletes, ModelPermissionTrait;

    public const ADMIN = 'admin';
    public const ROLES = [
        self::ADMIN,
        'editor',
    ];

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Связь обратная многие ко многим.
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Связь многие ко многим для любых моделей.
     * @return MorphToMany
     */
    public function permissions(): MorphToMany
    {
        return $this->morphToMany(
            Permission::class,
            'permissionable',
        )->withTimestamps();
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

            // При мягком удалении не сработает
            if ($model->isForceDeleting()) {

                // Удаление связанного элемента
                $model->permissions()->delete();
            }

            // Удалить кэш
            cache()->flush();
        });
    }
}
