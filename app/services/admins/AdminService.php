<?php

namespace App\services\admins;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;

class AdminService {

    public function index() {

        $admins = User::where('type', 'admin')->get();
        if(count($admins) > 0) {

            $admins = AdminResource::collection($admins);
            return $admins;

        } else {

            throw new Exception('Not Found Admins');

        }

    }

    public function store($request) {
        
        $admin = User::create([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'password'  => Hash::make($request['password']),
            'gender'    => $request['gender'],
            'type'      => 'admin',
            'is_active' => $request['is_active']
        ]);
        $admin->assignRole('admin');
        
    }

    public function update($request, User $user) {

        $user = User::where('id', $user->id)->where('type', 'admin')->first();
        $user->update([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'password'  => Hash::make($request['password']),
            'gender'    => $request['gender'],
            'type'      => 'admin',
            'is_active' => $request['is_active']
        ]);

    }

    public function destroy(User $user) {

        $user = User::where('id', $user->id)->where('type', 'admin')->first();
        $user->delete();

    }

}