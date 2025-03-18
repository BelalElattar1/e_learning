<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\services\password\PasswordResetService;

class PasswordReset extends Controller
{
    use ResponseTrait;

    protected $password_rest_service;

    public function __construct(PasswordResetService $password_reset_service)
    {
        $this->password_rest_service = $password_reset_service;
    }

    public function send_reset_code(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50|exists:users,email',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {

            $this->password_rest_service->send_reset_code($request->all());
            return $this->response('The code has been sent to your email successfully');
        
        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function reset_password(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|email|max:50|exists:password_reset_codes,email',
            'code'      => 'required|string|max:6|min:6|exists:password_reset_codes,code',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {

            $this->password_rest_service->reset_password($request->all());
            return $this->response('Password Has Been Reset Suc');

        } catch (Exception $e) {

            return $this->response('An error has occurred, please try again', 500);

        }

    }

}
