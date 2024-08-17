<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Us\{MainController, DummyController};

Route::get('', [MainController::class, 'index'])
    ->name('main-index');

Route::get('dummy', [DummyController::class, 'index'])
    ->name('dummy-index');
Route::get('/dummy/{slug}', [DummyController::class, 'show'])
    ->name('dummy-show');
