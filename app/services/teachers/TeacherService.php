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

    public function show_all() {

        $teachers = User::select('id', 'name', 'email', 'gender')
                    ->where('type', 'teacher')
                    ->with(['teacher:id,user_id,material_id,phone_number,is_subscriber', 'teacher.material:id,name'])
                    ->get();

        return $teachers ? TeacherResource::collection($teachers) : throw new Exception('Not Found Teachers');

    }

    public function index(Material $material) {

        $teachers = User::select('id', 'name', 'email', 'gender')
                    ->where('type', 'teacher')
                    ->whereRelation('teacher', 'material_id', $material->id)
                    ->with(['teacher:id,user_id,material_id,phone_number,is_subscriber', 'teacher.material:id,name'])
                    ->get();

        return $teachers ? TeacherResource::collection($teachers) : throw new Exception('Not Found Teachers');

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
            ...$request->only(['name', 'email', 'gender']),
            'password' => Hash::make($request['password']),
            'type'     => 'teacher',
            'is_active' => 1
        ]);

    }
    
    private function create_teacher($request, User $user) {

        return Teacher::Create([
            ...$request->only(['phone_number', 'material_id']),
            'user_id' => $user->id
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
        
        abort_if($user->type !== 'teacher', 404, 'User is not an teacher');
        $user->update([
            ...$request->only(['name', 'email', 'gender']),
            'password' => Hash::make($request['password'])
        ]);

        $user->teacher->update([
            ...$request->only(['phone_number', 'material_id']),
        ]);

    }

    public function destroy(User $user) {

        abort_if($user->type !== 'teacher', 404, 'User is not an teacher');
        $user->update([
            'is_active' => 0
        ]);

    }

}