<?php

namespace App\services\students;

use Exception;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\StudentResource;

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
            ...$request->only(['name', 'email', 'gender']),
            'password' => Hash::make($request['password'])
        ]);

    }

    private function create_student($request, User $user) {

        return Student::create([
            ...$request->only(['phone_number', 'father_phone', 'mother_phone', 'school_name', 'father_job', 'mayor_id', 'academic_year_id']),
            'card_photo' => store_image($request['card_photo'], 'cards'),
            'user_id'    => $user->id
        ]);

    }

    public function get_student() {

        throw_unless($user = JWTAuth::parseToken()->authenticate(), new Exception('User not found'));

        return new StudentResource($user->load('student', 'student.academic_year:id,name', 'student.mayor:id,name'));

    }

    public function get_all_students() {

        $users = User::select('id', 'name', 'email')->where('is_active', 1)->where('type', 'student')->with('student', 'student.academic_year:id,name', 'student.mayor:id,name')->get();
        return $users ? StudentResource::collection($users) : throw new Exception('There are no inactive students');

    }

    public function get_all_students_inactive() {

        $users = User::select('id', 'name', 'email')->where('is_active', 0)->where('type', 'student')->with('student', 'student.academic_year:id,name', 'student.mayor:id,name')->get();
        return $users ? StudentResource::collection($users) : throw new Exception('There are no inactive students'); 

    }

    public function student_activation(User $user) {
       
        abort_if($user->type !== 'student', 404, 'User is not an student');
        $user->update([
            'is_active' => 1
        ]);

    }

}