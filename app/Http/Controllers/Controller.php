<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\InfoTrait;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, InfoTrait;

    public function __construct()
    {
        // Подключаем нужные св-ва класса для определения рефлексии
        $this->info();

        // Конструкция для классов и методов, которые запускаются после конструктора
        $this->middleware(function ($request, $next) {

            // Сохраним Url, с которого перешёл пользователь из админки
            $this->saveAdminPreviousUrl();

            return $next($request);
        });

        // Передаём в вид переменные
        view()->share([
            'snake' => $this->snake, // class_dummy
            'kebab' => $this->kebab, // class-dummy
            'route' => $this->route,
        ]);
    }

    /**
     * Сохраняем в сессию страницу с которой авторизированный пользователь перешёл из админки.
     * @return void
     */
    private function saveAdminPreviousUrl(): void
    {
        // Если пользователь авторизирован и url содержит админский slug
        if (auth()->check() && Str::is('*' . config('filament.slug') . '*', url()->previous())) {
            session()->put('back_link_admin', url()->previous());
        }
    }
}
