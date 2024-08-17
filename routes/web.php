<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Us\{DummyController, FormController, MainController};

Route::get('', [MainController::class, 'index'])
    ->name('main');

Route::post('form-new', [FormController::class, 'formNew'])
    ->name('form-new');

Route::get('dummy', [DummyController::class, 'index'])
    ->name('dummy-index');
Route::get('/dummy/{slug}', [DummyController::class, 'show'])
    ->name('dummy-show');
