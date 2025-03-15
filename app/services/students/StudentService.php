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
                throw new Exception('User not found');
            }

            $user = new StudentResource($user->load('student.academic_year', 'student.mayor'));
            return $user;

        } catch (JWTException $e) {

            throw new Exception('Invalid token'); 

        }

    }

    public function get_all_students_inactive() {

        $users = User::where('is_active', 0)->where('type', 'student')->with('student.academic_year', 'student.mayor')->get();
        $users = StudentResource::collection($users);

        if(count($users) > 0) {

            return $users;

        } else {

            throw new Exception('There are no inactive students');

        }  

    }

    public function student_activation($id) {
                
        User::findOrFail($id)->update([
            'is_active' => 1
        ]);

    }

}