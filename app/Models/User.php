<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Interfaces\ModelPermissionInterface;
use App\Traits\ModelPermissionTrait;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;

/**
 *
 * admin СУПЕР ПОЛЬЗОВАТЕЛЬ.
 * Для роли админ все роли и разрешения возвращают true.
 *
 */
class User extends Authenticatable implements FilamentUser, HasAvatar, ModelPermissionInterface
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, ModelPermissionTrait;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Связь обратная многие ко многим.
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            // При мягком удалении не сработает
            if ($model->isForceDeleting()) {
                // Удаление связанного элемента
                $model->roles()->delete();
                $model->permissions()->delete();
            }
        });
    }

    /**
     * Возвращает картинку gravatar.
     * @param string|null $email
     * @return string|null
     */
    public static function gravatar(?string $email): ?string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'https://www.gravatar.com/avatar/' . md5(Str::lower($email));
        }
        return null;
    }

    /**
     * Gravatar
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return self::gravatar($this->email);
    }

    /**
     * Кому разрешён доступ в админку.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles?->pluck('name')?->toArray();
    }

    /**
     * @param array|string|null $roles
     * @return bool
     */
    public function isRole(array|string|null $roles): bool
    {
        $userRoles = $this->getRoles();
        if (is_array($roles)) {
            // Если admin true
            if (in_array(Role::ADMIN, $userRoles)) {
                return true;
            }
            foreach ($roles as $role) {
                if (in_array($role, $userRoles)) {
                    return true;
                }
            }
        } else {
            if (in_array($roles, $userRoles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * admin СУПЕРПОЛЬЗОВАТЕЛЬ
     * Для роли админ все роли и разрешения возвращают true.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(Role::ADMIN, $this->getRoles());
    }
}
