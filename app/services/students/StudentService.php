<?php

namespace App\services\students;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\StudentResource;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;

class StudentService {

    public function register_student($request) {

        DB::transaction(function () use ($request) {   

            $user = $this->create_user($request);
            $this->create_student($request, $user);
            $user->assignRole('student');

        });

        return response()->json([
            'Message' => 'The account has been created successfully. You can contact support to activate the account'
        ], 201);

    }

    private function create_user($request) {

        return User::create([
            'name'     => $request['name'], 
            'email'    => $request['email'], 
            'gender'   => $request['gender'],
            'password' => Hash::make($request['password'])
        ]);

    }

    private function create_student($request, User $user) {

        return Student::create([
            'phone_number'      => $request['phone_number'],
            'father_phone'      => $request['father_phone'],
            'mother_phone'      => $request['mother_phone'],
            'school_name'       => $request['school_name'],
            'father_job'        => $request['father_job'],
            'card_photo'        => store_image($request['card_photo'], 'cards'),
            'mayor_id'          => $request['mayor_id'],
            'academic_year_id'  => $request['academic_year_id'],
            'user_id'           => $user->id
        ]);

    }

    public function get_student() {

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

        } catch (JWTException $e) {

            return response()->json(['error' => 'Invalid token'], 400);

        }

        $user = new StudentResource($user->load('student.academic_year', 'student.mayor'));
        return response()->json([
            'data'    => $user,
            'Message' => 'User data retrieved successfully.'
        ]);

    }

    public function get_all_students_inactive() {

        $users = User::where('is_active', 0)->where('type', 'student')->with('student.academic_year', 'student.mayor')->get();
        $users = StudentResource::collection($users);

        if(count($users) > 0) {

            return response()->json([
                'data' => $users,
                'Massege' => 'All students have been successfully recruited'
            ]);

        } else {

            return response()->json([
                'Message' => 'There are no inactive students'
            ], 404);

        }

    }

    public function student_activation($id) {

        User::findOrFail($id)->update([
            'is_active' => 1
        ]);
        
        return response()->json([
            'Message' => 'This student has been activated successfully'
        ], 200);

    }

}