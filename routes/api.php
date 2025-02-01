<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
});