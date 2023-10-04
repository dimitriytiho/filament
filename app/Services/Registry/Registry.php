<?php

namespace App\Services\Registry;

/*

Реализация паттерн Регистр.

Использование:
$registry = new \App\Services\Registry\Registry();
$registry->set('test', [1, 2]);
dump($registry->all());

 */
class Registry implements RegistryInterface
{
    private array $properties = [];

    /**
     * Has key property.
     *
     * @param int|string|null $key
     * @return bool
     */
    public function has(int|string|null $key): bool
    {
        return isset($this->properties[$key]);
    }

    /**
     * Set property value.
     *
     * @param string|int $key
     * @param mixed $value
     * @return void
     */
    public function set(string|int $key, mixed $value = null): void
    {
        $this->properties[$key] = $value;
    }


    /**
     * Get property value.
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return $this->properties[$key] ?? $default;
    }


    /**
     * Get all properties.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->properties;
    }

    /**
     * Unset property value.
     *
     * @param string|int|null $key
     * @return bool
     */
    public function unset(string|int|null $key): bool
    {
        if ($this->has($key)) {
            unset($this->properties[$key]);
            return true;
        }
        return false;
    }

    /**
     * Unset all properties.
     *
     * @return void
     */
    public function unsetAll(): void
    {
        $this->properties = [];
    }
}
