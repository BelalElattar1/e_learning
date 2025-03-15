<?php

namespace App\services\auth;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {

    public function login($request) {

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password']
        ];

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Get the authenticated user.
        $user = auth()->user();

        if(!$user->is_active) {
            return response()->json(['Message' => 'This account is not activated. You can contact support'], 403);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'Role'        => $user->type,
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'Token'       => $token
        ]);

    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

}