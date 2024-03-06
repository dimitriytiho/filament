<?php

namespace App\Traits;

use App\Models\Role;

trait ModelPermissionTrait
{
    /**
     * admin - СУПЕР ПОЛЬЗОВАТЕЛЬ.
     * Для роли админ все роли и разрешения true.
     *
     * @return bool
     */
    public static function userIsAdmin(): bool
    {
        if (auth()->check()) {
            return in_array(Role::ADMIN, auth()->user()->getRoles());
        }
        return false;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions?->pluck('name')?->toArray();
    }

    /**
     * @param array|string|null $permissions
     * @return bool
     */
    public function isPermission(array|string|null $permissions): bool
    {
        // Если admin true
        if (self::userIsAdmin()) {
            return true;
        }
        $userPermissions = $this->getPermissions();
        if (is_array($permissions)) {
            foreach ($permissions as $role) {
                if (in_array($role, $userPermissions)) {
                    return true;
                }
            }
        } else {
            if (in_array($permissions, $userPermissions)) {
                return true;
            }
        }
        return false;
    }
}
