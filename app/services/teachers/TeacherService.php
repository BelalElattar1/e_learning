<?php

namespace App\services\teachers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Material;
use App\Models\Subscribe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\TeacherResource;

class TeacherService {

    public function index(Material $material) {

        $teachers = User::where('type', 'teacher')
        ->whereRelation('teacher', 'material_id', $material->id)
        ->with('teacher.material')
        ->get();

        return count($teachers) > 0 
        ? TeacherResource::collection($teachers) 
        : throw new Exception('Not Found Teachers');

    }

    public function store($request) {

        DB::transaction(function () use ($request) {   

            $user    = $this->create_user($request);
            $teacher = $this->create_teacher($request, $user);
            $this->create_subscribtion_for_teacher($request, $teacher);
            $user->assignRole('teacher');

        });

    }

    private function create_user($request) {

        return User::create([
            'name'     => $request['name'],
            'email'    => $request['email'],
            'password' => Hash::make($request['password']),
            'gender'   => $request['gender'],
            'type'     => 'teacher',
            'is_active' => 1
        ]);

    }
    
    private function create_teacher($request, User $user) {

        return Teacher::Create([
            'phone_number' => $request['phone_number'],
            'material_id'  => $request['material_id'],
            'user_id'      => $user->id
        ]);

    }

    private function create_subscribtion_for_teacher($request, Teacher $teacher) {

        return Subscribe::create([
            'start'      => Carbon::now()->format("Y-m-d"),
            'end'        => Carbon::now()->addMonth()->format("Y-m-d"),
            'pay_photo'  => store_image($request['pay_photo'], 'subscribes'),
            'status'     => 'active',
            'teacher_id' => $teacher->id
        ]);

    }

    public function update($request, User $user) {

        $user = User::where('id', $user->id)->where('type', 'teacher')->first();
        $user->update([
            'name'     => $request['name'],
            'email'    => $request['email'],
            'password' => Hash::make($request['password']),
            'gender'   => $request['gender']
        ]);

        $user->teacher->update([
            'phone_number' => $request['phone_number'],
            'material_id'  => $request['material_id']
        ]);

    }

    public function destroy(User $user) {

        User::where('id', $user->id)->where('type', 'teacher')->update([
            'is_active' => 0
        ]);

    }

}