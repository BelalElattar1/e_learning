<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    JWTAuthController,
    PasswordReset,
    AdminController,
    TeacherController,
    ImageController,
    SubscribeController,
};

use App\Models\{
    AcademicYear,
    Material,
    Mayor
};

Route::group(['middleware' => 'JwtAuth'], function () {

    //  User Auth
    Route::prefix('user')->controller(JWTAuthController::class)->group(function () {
        Route::get('/show', 'get_student');
        Route::get('/get_all_students_inactive', 'get_all_students_inactive')->middleware(['permission:get_all_students_inactive']);
        Route::put('/student_activation/{id}', 'student_activation')->middleware(['permission:student_activation']);
        Route::post('/logout', 'logout');
    });

    // Admin Controller
    Route::prefix('admins')->controller(AdminController::class)->group(function () {
        Route::get('/index', 'index')->middleware(['permission:show_all_admins']);
        Route::post('/store', 'store')->middleware(['permission:create_admin']);
        Route::put('/update/{user}', 'update')->middleware(['permission:update_admin']);
        Route::delete('/destroy/{user}', 'destroy')->middleware(['permission:delete_admin']);
    });

    // Teacher Controller
    Route::prefix('teachers')->controller(TeacherController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:create_teacher']);
        Route::put('/update/{user}', 'update')->middleware(['permission:update_teacher']);
        Route::delete('/destroy/{user}', 'destroy')->middleware(['permission:delete_teacher']);
    });

    // Image Controller
    Route::prefix('images')->controller(ImageController::class)->group(function () {
        Route::post('/get_private_image/{folder}/{filename}', 'get_private_image');
    });

    // Subscribes Controller
    Route::prefix('subscribes')->controller(SubscribeController::class)->group(function () {
        Route::get('/index', 'index')->middleware(['permission:show_all_subscribes']);
        Route::post('/store', 'store')->middleware(['permission:create_subscribe']);
        Route::put('/update_subscription_status/{subscribe}', 'update_subscription_status')->middleware(['permission:update_subscription_status']);
        Route::delete('/destroy/{subscribe}', 'destroy')->middleware(['permission:delete_subscribe']);
    });

});

// Auth
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

// Forget Password
Route::post('forget_password', [PasswordReset::class, 'send_reset_code']);
Route::post('reset_password', [PasswordReset::class, 'reset_password']);

// Teacher Controller
Route::get('teachers/index', [TeacherController::class, 'index']);

// AcademicYear Controller
Route::get('academic_years/index', function () {
    return AcademicYear::pluck('name');
});

// Mayors Controller
Route::get('mayors/index', function () {
    return Mayor::pluck('name');
});

// Materials Controller
Route::get('materials/index', function () {
    return Material::pluck('name');
});
