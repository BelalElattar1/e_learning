<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;

Route::group(['middleware' => 'JwtAuth'], function () {

    // User Auth
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/user', [JWTAuthController::class, 'getUser']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
    });

});

// Auth
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);
