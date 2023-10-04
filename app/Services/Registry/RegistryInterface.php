<?php

namespace App\Services\Registry;

interface RegistryInterface
{
    /**
     * @param string|int|null $key
     * @return bool
     */
    public function has(string|int|null $key): bool;

    /**
     * @param string|int $key
     * @param mixed|null $value
     * @return void
     */
    public function set(string|int $key, mixed $value = null): void;

    /**
     * @param string|int $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string|int $key, mixed $default = null): mixed;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @return bool
     * @param string|int|null $key
     */
    public function unset(string|int|null $key): bool;

    /**
     * @return void
     */
    public function unsetAll(): void;
}
