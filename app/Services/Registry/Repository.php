<?php

namespace App\Services\Registry;

use Illuminate\Config\Repository as RepositoryIlluminate;

/*

Реализация паттерн Регистр, через Illuminate\Config\Repository, возможность использовать во всём приложении.

// Использование:
app('registry')->set('new', [1, 2, 3]);
$new = app('registry')->get('new');
$all = app('registry')->all();

 */
class Repository extends RepositoryIlluminate
{
    /**
     * @param string $key
     * @return bool
     */
    public function unset(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->items[$key]);
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    public function unsetAll(): void
    {
        $this->items = [];
    }
}
