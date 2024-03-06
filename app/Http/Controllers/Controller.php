<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\InfoTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, InfoTrait;

    public function __construct()
    {
        // Подключаем нужные св-ва класса для определения рефлексии
        $this->info();

        // Передаём в вид переменные
        view()->share([
            'snake' => $this->snake, // class_dummy
            'kebab' => $this->kebab, // class-dummy
            'route' => $this->route,
        ]);
    }
}
