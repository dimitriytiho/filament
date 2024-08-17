<?php

use App\Http\Middleware\Ip;
use App\Http\Controllers\ApiV1\{MenuController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('api.v1.')
    ->prefix('api/v1')
    //->middleware(Ip::class) // Проверка по IP
    ->group(function () {

        /*
         * POST http://127.0.0.1:8004/api/v1/menu
         * route('api.v1.menu')
         */
        Route::post('menu', [MenuController::class, 'index'])
            ->name('menu');
    });
