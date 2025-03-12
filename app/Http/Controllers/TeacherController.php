<?php

namespace App\Http\Controllers;

use App\Http\Requests\admins\UpdateAdminRequest;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\ResponseTrait;
use App\Models\Teacher;
use App\Models\Subscribe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\teachers\StoreTeacherRequest;
use App\Http\Requests\teachers\UpdateTeacherRequest;

class TeacherController extends Controller
{
    use ResponseTrait;

    public function index() {

        $teachers = User::where('type', 'teacher')->with('teacher.material')->get();
        $teachers = TeacherResource::collection($teachers);
        return $this->response('Show All Admin Suc', 201, $teachers);

    }

    public function store(StoreTeacherRequest $request) {
        
        try {

            DB::beginTransaction();

                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'gender'   => $request->gender,
                    'type'     => 'teacher',
                    'is_active' => 1
                ]);
                $user->assignRole('teacher');
        
                $teacher = Teacher::Create([
                    'phone_number' => $request->phone_number,
                    'material_id'  => $request->material_id,
                    'user_id'      => $user->id
                ]);

                Subscribe::create([
                    'start' => Carbon::now()->format("Y-m-d"),
                    'end'   => Carbon::now()->addMonth()->format("Y-m-d"),
                    'pay_photo' => store_image($request->file('pay_photo'), 'subscribes'),
                    'status' => 'active',
                    'teacher_id' => $teacher->id
                ]);


            DB::commit();

            return response()->json([
                'Message' => 'The teacher was created successfully.'
            ], 201);

        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => $e
            ], 500);
            
        }


    }

    public function update(UpdateTeacherRequest $request, User $user) {

        try {

            $user = User::where('id', $user->id)->where('type', 'teacher')->first();
            $user->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'gender'   => $request->gender,
                'type'     => 'teacher',
                'is_active' => 1
            ]);

            $user->teacher->update([
                'phone_number' => $request->phone_number,
                'material_id'  => $request->material_id
            ]);

            return response()->json([
                'Message' => 'The teacher was Updated successfully.'
            ], 201);

        } catch (Exception $e) {

            return response()->json([
                'message' => $e
            ], 500);
            
        }

    }

    public function destroy(User $user) {

        $user = User::where('id', $user->id)->where('type', 'teacher')->update([
            'is_active' => 0
        ]);
        return $this->response('The teacher account has been successfully disabled.');

    }

}
