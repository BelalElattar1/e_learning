<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\ResponseTrait;
use App\services\admins\AdminService;
use App\Http\Requests\admins\StoreAdminRequest;
use App\Http\Requests\admins\UpdateAdminRequest;

class AdminController extends Controller
{    
    use ResponseTrait;

    public $admin_service;

    public function __construct(AdminService $admin_service)
    {
        $this->admin_service = $admin_service;
    }

    public function index() {

        try {

            $data = $this->admin_service->index();
            return $this->response('Show All Admin Suc', 201, $data);

        } catch(Exception $e) {

            return response()->json(
                ['error' => $e->getMessage()
            ], 500);

        }

    }

    public function store(StoreAdminRequest $request) {

        try {

            $this->admin_service->store($request->all());
            return $this->response('The admin has been created successfullyc');

        } catch(Exception $e) {

            return response()->json(
                ['error' => $e->getMessage()
            ], 500);

        }

    }

    public function update(UpdateAdminRequest $request, User $user) {

        try {

            $this->admin_service->update($request->all(), $user);
            return $this->response('The admin has been Updated successfullyc');

        } catch(Exception $e) {

            return response()->json(
                ['error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(User $user) {

        try {

            $this->admin_service->destroy($user);
            return $this->response('The admin has been Deleted successfullyc');

        } catch(Exception $e) {

            return response()->json(
                ['error' => $e->getMessage()
            ], 500);

        }

    }

}
