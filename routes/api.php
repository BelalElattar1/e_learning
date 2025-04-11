<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    JWTAuthController,
    PasswordReset,
    AdminController,
    AnswerController,
    TeacherController,
    ImageController,
    SubscribeController,
    CourseController,
    CodeController,
    ChargeController,
    BuyingController,
    CategoryController,
    DegreeController,
    QuestionController,
    SectionController,
    MaterialController,
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
        Route::put('/student_activation/{user}', 'student_activation')->middleware(['permission:student_activation']);
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

    // Materials Controller
    Route::prefix('materials')->controller(MaterialController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:create_material']);
        Route::put('/update/{material}', 'update')->middleware(['permission:update_material']);
        Route::delete('/destroy/{material}', 'destroy')->middleware(['permission:delete_material']);
    });    

    // Subscribes Controller
    Route::prefix('subscribes')->controller(SubscribeController::class)->group(function () {
        Route::get('/index', 'index')->middleware(['permission:show_all_subscribes']);
        Route::get('/filter/{status}', 'filter')->middleware(['permission:filter_subscribe']);
        Route::post('/store', 'store')->middleware(['permission:create_subscribe']);
        Route::put('/update_subscription_status/{subscribe}', 'update_subscription_status')->middleware(['permission:update_subscription_status']);
        Route::delete('/destroy/{subscribe}', 'destroy')->middleware(['permission:delete_subscribe']);
    });

    // Courses Controller
    Route::prefix('courses')->controller(CourseController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:creat_course']);
        Route::post('/update/{course}', 'update')->middleware(['permission:update_course']);
    });

    // Code Controller
    Route::prefix('codes')->controller(CodeController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:create_code']);
    });
    
    // Chaarges Controller
    Route::prefix('charges')->controller(ChargeController::class)->group(function () {
        Route::post('/charge', 'charge')->middleware(['permission:charge']);
        Route::get('/show_all_charges', 'show_all_charges')->middleware(['permission:show_all_charges']);
        Route::get('/show_all_wallets', 'show_all_wallets')->middleware(['permission:show_all_wallets']);
    });

    // Buyings Controller
    Route::prefix('buyings')->controller(BuyingController::class)->group(function () {
        Route::post('/buying/{course}', 'buying')->middleware(['permission:buying']);
        Route::get('/my_courses', 'my_courses')->middleware(['permission:my_courses']);
    });

    // Categories Controller
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:create_category']);
        Route::put('/update/{category}', 'update')->middleware(['permission:update_category']);
        Route::delete('/destroy/{category}', 'destroy')->middleware(['permission:delete_category']);
    });

    // Section Controller
    Route::prefix('sections')->controller(SectionController::class)->group(function () {
        Route::get('/show/{section}', 'show');
        Route::post('/store', 'store')->middleware(['permission:create_section']);
        Route::put('/update/{section}', 'update')->middleware(['permission:update_section']);
        Route::delete('/destroy/{section}', 'destroy')->middleware(['permission:delete_section']);
    });

    // Questions Controller
    Route::prefix('questions')->controller(QuestionController::class)->group(function () {
        Route::post('/store', 'store')->middleware(['permission:create_question']);
        Route::delete('/destroy/{question}', 'destroy')->middleware(['permission:delete_question']);
    });

    // Answer Controller
    Route::prefix('answers')->controller(AnswerController::class)->group(function () {
        Route::post('/answer', 'answer')->middleware(['permission:answer']);
    });

    // Degrees Controller
    Route::prefix('degrees')->controller(DegreeController::class)->group(function () {
        Route::get('/show_all_degrees', 'show_all_degrees')->middleware(['permission:show_all_degrees']);
        Route::get('/show_exam_answers/{exam}', 'show_exam_answers');//->middleware(['permission:show_exam_answers']);
    });

});

// Auth
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

// Forget Password
Route::post('forget_password', [PasswordReset::class, 'send_reset_code']);
Route::post('reset_password', [PasswordReset::class, 'reset_password']);

// Teacher Controller
Route::get('teachers/index/{material}', [TeacherController::class, 'index']);
Route::get('teachers/show_all', [TeacherController::class, 'show_all']);

// Course Controller
Route::prefix('courses')->controller(CourseController::class)->group(function () {
    Route::get('/index/{teacher}', 'index');
    Route::get('/show/{course}', 'show');
});

// AcademicYear Controller
Route::get('academic_years/index', function () {
    return AcademicYear::pluck('name');
});

// Mayors Controller
Route::get('mayors/index', function () {
    return Mayor::pluck('name');
});

// Materials Controller
Route::get('materials/index/{id?}', function ($id = null) {
    if ($id) {
        return Material::where('academic_year_id', $id)->pluck('name');
    } else {
        return Material::pluck('name');
    }
});
