<?php

namespace App\Interfaces;

interface ModelPermissionInterface
{
    /**
     * admin СУПЕР ПОЛЬЗОВАТЕЛЬ.
     * Для роли админ все роли и разрешения true.
     *
     * @return bool
     */
    public static function userIsAdmin(): bool;

    /**
     * @return array
     */
    public function getPermissions(): array;

    /**
     * @param array|string|null $permissions
     * @return bool
     */
    public function isPermission(array|string|null $permissions): bool;
}
