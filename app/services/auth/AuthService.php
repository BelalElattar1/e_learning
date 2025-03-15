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
            throw new Exception('Invalid credentials'); 
        }

        // Get the authenticated user.
        $user = auth()->user();

        if(!$user->is_active) {
            throw new Exception('This account is not activated. You can contact support'); 
        }

        $token = JWTAuth::fromUser($user);

        return [
            'Role'        => $user->type,
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'Token'       => $token
        ];

    }

    public function logout()
    {

        JWTAuth::invalidate(JWTAuth::getToken());

    }

}