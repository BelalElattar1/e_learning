<?php

namespace App\services\admins;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;

class AdminService {

    public function index() {

        $admins = User::where('type', 'admin')
                ->select('id', 'name', 'email', 'gender', 'is_active')
                ->get();

        abort_if($admins->isEmpty(), 404, 'No admins found');
        return AdminResource::collection($admins);

    }

    public function store($request) {
        
        $admin = User::create([
            ...$request->only(['name', 'email', 'gender', 'is_active']),
            'password' => Hash::make($request['password']),
            'type'     => 'admin',
        ]);
        $admin->assignRole('admin');
        
    }

    public function update($request, User $user) {

        abort_if($user->type !== 'admin', 404, 'User is not an admin');
        $user->update([
            ...$request->only(['name', 'email', 'gender', 'is_active']),
            'password' => Hash::make($request['password']),
            'type'     => 'admin',
        ]);

    }

    public function destroy(User $user) {

        abort_if($user->type !== 'admin', 404, 'User is not an admin');
        $user->delete();

    }

}