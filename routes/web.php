<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Us\{MainController, DummyController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', [MainController::class, 'index'])
    ->name('main-index');

Route::get('dummy', [DummyController::class, 'index'])
    ->name('dummy-index');
Route::get('/dummy/{slug}', [DummyController::class, 'show'])
    ->name('dummy-show');
