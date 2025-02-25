<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    JWTAuthController,
    PasswordReset,
};

use App\Models\{
    AcademicYear,
    Mayor
};

Route::group(['middleware' => 'JwtAuth'], function () {

    // User Auth
    Route::group(['prefix' => 'user'], function () {
        Route::get('/show', [JWTAuthController::class, 'getUser']);
        Route::get('/get_all_students_inactive', [JWTAuthController::class, 'get_all_students_inactive'])->middleware(['permission:get_all_students_inactive']);
        Route::get('/student_activation/{id}', [JWTAuthController::class, 'student_activation'])->middleware(['permission:student_activation']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
    });

});

// Auth
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

// Forget Password
Route::post('forget_password', [PasswordReset::class, 'send_reset_code']);
Route::post('reset_password', [PasswordReset::class, 'reset_password']);

// AcademicYear Controller
Route::get('academic_years/index', function () {
    return AcademicYear::all();
});

// AcademicYear Controller
Route::get('mayors/index', function () {
    return Mayor::all();
});
