<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\services\auth\AuthService;
use App\services\students\StudentService;
use App\Http\Requests\students\StoreStudentRequest;

class JWTAuthController extends Controller
{
    protected $student_service; 
    protected $auth_service; 

    public function __construct(StudentService $student_service, AuthService $auth_service) 
    {
        
        $this->auth_service    = $auth_service;
        $this->student_service = $student_service;

    }

    // User registration
    public function register(StoreStudentRequest $request)
    {
        
        try {

            $this->student_service->register_student($request);
            return response()->json([
                'Message' => 'The account has been created successfully. You can contact support to activate the account'
            ], 201);
        
        } catch (Exception $e) {

            return response()->json([
                'Message' => $e->getMessage()
            ], 500); 

        }

    }

    // User login
    public function login(Request $request)
    {

        try {

            $data =  $this->auth_service->login($request);
            return response()->json([
                'data' => $data
            ]);

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    // Get authenticated user
    public function get_student()
    {

        try {

            $data = $this->student_service->get_student();
            return response()->json([
                'data'    => $data,
                'Message' => 'User data retrieved successfully.'
            ]);

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function get_all_students_inactive() {

        try {

            $data = $this->student_service->get_all_students_inactive();
            return response()->json([
                'data' => $data,
                'Massege' => 'All students have been successfully recruited'
            ]);

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }


    }

    public function student_activation($id) {

        try {

            $this->student_service->student_activation($id);
            return response()->json([
                'Message' => 'This student has been activated successfully'
            ], 200);
    

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    // User logout
    public function logout()
    {

        try {

            $this->auth_service->logout();
            return response()->json(['message' => 'Successfully logged out']); 

        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
