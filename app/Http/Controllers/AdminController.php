<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\admins\StoreAdminRequest;
use App\Http\Requests\admins\UpdateAdminRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\ResponseTrait;

class AdminController extends Controller
{    
    use ResponseTrait;

    public function index() {

        $admins = User::where('type', 'admin')->get();
        $admins = AdminResource::collection($admins);
        return $this->response('Show All Admin Suc', 201, $admins);

    }

    public function store(StoreAdminRequest $request) {

        $admin = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'gender'    => $request->gender,
            'type'      => 'admin',
            'is_active' => $request->is_active
        ]);
        $admin->assignRole('admin');

        return $this->response('The admin has been created successfullyc');

    }

    public function update(UpdateAdminRequest $request, User $user) {

        $user = User::where('id', $user->id)->where('type', 'admin')->first();
        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'gender'    => $request->gender,
            'type'      => 'admin',
            'is_active' => $request->is_active
        ]);

        return $this->response('The admin has been Updated successfullyc');

    }

    public function destroy(User $user) {

        $user = User::where('id', $user->id)->where('type', 'admin')->first();
        $user->delete();
        return $this->response('The admin has been successfully deleted');

    }

}
