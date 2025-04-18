<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\ResponseTrait;
use App\Models\Material;
use App\services\teachers\TeacherService;
use App\Http\Requests\teachers\StoreTeacherRequest;
use App\Http\Requests\teachers\UpdateTeacherRequest;

class TeacherController extends Controller
{
    use ResponseTrait;

    protected $teacher_srvice;

    public function __construct(TeacherService $teacher_service)
    {
        $this->teacher_srvice = $teacher_service;
    }

    public function show_all() {

        try {

            $data = $this->teacher_srvice->show_all();
            return $this->response('Show All Teachers Suc', 201, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function index(Material $material) {

        try {

            $data = $this->teacher_srvice->index($material);
            return $this->response('Show All Teachers Suc', 201, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function store(StoreTeacherRequest $request) {

        try {

            $this->teacher_srvice->store($request);
            return response()->json([
                'Message' => 'The teacher was created successfully.'
            ], 201);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function update(UpdateTeacherRequest $request, User $user) {

        try {

            $this->teacher_srvice->update($request, $user);
            return response()->json([
                'Message' => 'The teacher was Updated successfully.'
            ], 201);

        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
            
        }

    }

    public function destroy(User $user) {

        try {

            $this->teacher_srvice->destroy($user);
            return $this->response('The teacher account has been successfully disabled.');

        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
            
        }

    }

}
