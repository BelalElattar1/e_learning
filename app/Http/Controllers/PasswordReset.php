<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\PasswordResetCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordReset extends Controller
{
    use ResponseTrait;

    public function send_reset_code(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {

            PasswordResetCode::whereEmail($request->email)->delete();

            $code = rand(100000, 999999);
            PasswordResetCode::create([
                'email'      => $request->email,
                'code'       => $code,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]);

            Mail::raw("Your Password Reset Code Is: $code", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Code Sent Your Email');
            });

            return $this->response('The code has been sent to your email successfully');
        
        } catch (Exception $e) {

            return $this->response('An error occurred sending the code, try again', 500);

        }

    }

    public function reset_password(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|email|max:255|exists:password_reset_codes,email',
            'code'      => 'required|string|exists:password_reset_codes,code',
            'password'  => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {

            $reset = PasswordResetCode::where('email', $request->email)
                ->where('code', $request->code)
                ->where('expires_at', '>=', Carbon::now())
                ->first();
            
            if(!$reset) {
                return $this->response('Invalid Or Expired Reset Code', 400);
            }

            User::whereEmail($reset->email)->update([
                'password' => Hash::make($request->password),
            ]);

            $reset->delete();
            return $this->response('Password Has Been Reset Suc');

        } catch (Exception $e) {

            return $this->response('An error has occurred, please try again', 500);

        }

    }

}
