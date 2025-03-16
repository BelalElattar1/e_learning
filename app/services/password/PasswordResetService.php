<?php

namespace App\services\password; 

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordResetCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetService {

    public function send_reset_code($request) {

        PasswordResetCode::whereEmail($request['email'])->delete();

        $code = rand(100000, 999999);
        PasswordResetCode::create([
            'email'      => $request['email'],
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        Mail::raw("Your Password Reset Code Is: $code", function ($message) use ($request) {
            $message->to($request['email'])
                    ->subject('Code Sent Your Email');
        });

    }

    public function reset_password($request) {

        $reset = PasswordResetCode::where('email', $request['email'])
                ->where('code', $request['code'])
                ->where('expires_at', '>=', Carbon::now())
                ->first();
    
        if(!$reset) {
            throw new Exception('Invalid Or Expired Reset Code');
        }

        User::whereEmail($reset->email)->update([
            'password' => Hash::make($request['password']),
        ]);

        $reset->delete();

    }

}