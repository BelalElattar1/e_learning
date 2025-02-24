<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    JWTAuthController,
    PasswordReset
};

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

// Forget Password
Route::post('forget_password', [PasswordReset::class, 'send_reset_code']);
Route::post('reset_password', [PasswordReset::class, 'reset_password']);

