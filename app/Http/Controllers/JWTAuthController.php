<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\services\auth\AuthService;
use App\services\students\StudentService;
use App\Http\Requests\students\StoreStudentRequest;

class JWTAuthController extends Controller
{
    public $student_service; 
    public $auth_service; 

    public function __construct(StudentService $student_service, AuthService $auth_service) 
    {
        
        $this->auth_service    = $auth_service;
        $this->student_service = $student_service;

    }

    // User registration
    public function register(StoreStudentRequest $request)
    {
        
        try {

            return $this->student_service->register_student($request->all());
        
        } catch (\Exception $e) {

            return response()->json([
                'Message' => 'Sorry, an error occurred. Please try again.'
            ], 500); 

        }

    }

    // User login
    public function login(Request $request)
    {

        try {

            return $this->auth_service->login($request->all());

        } catch (Exception $e) {

            return response()->json(['error' => 'Sorry, an error occurred. Please try again.'], 500);

        }

    }

    // Get authenticated user
    public function get_student()
    {

        try {

            return $this->student_service->get_student();

        } catch (Exception $e) {

            return response()->json(['error' => 'Sorry, an error occurred. Please try again.'], 500);

        }

    }

    public function get_all_students_inactive() {

        try {

            return $this->student_service->get_all_students_inactive();

        } catch (Exception $e) {

            return response()->json(['error' => 'Sorry, an error occurred. Please try again.'], 500);

        }


    }

    public function student_activation($id) {

        try {

            return $this->student_service->student_activation($id);

        } catch (Exception $e) {

            return response()->json(['error' => 'Sorry, an error occurred. Please try again.'], 500);

        }

    }

    // User logout
    public function logout()
    {

        try {

            return $this->auth_service->logout();

        } catch (Exception $e) {

            return response()->json(['error' => 'Sorry, an error occurred. Please try again.'], 500);

        }

    }

}
