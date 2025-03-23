<?php

namespace App\Services\charges;

use Exception;
use App\Models\Code;
use App\Models\Charge;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ChargeResource;
use App\Http\Resources\WalletResource;

class ChargeService
{
    public function charge($request) {

        $user = auth()->user();
        $code = Code::where('code', $request['code'])->where('is_active', 1)->first();
        throw_unless($code, new Exception('This code is not activated'));

        DB::transaction(function () use ($code, $user) {  

            $this->create_charge($code, $user);
            $this->create_wallet($code, $user);

            // بحط اي فلوس بتتشحن للمدرس ده عشان يبقى عارف كل الفلوس اللي دخلت من اول ما دخل الموقع
            $code->teacher->user->update([
                'wallet' => $code->teacher->user->wallet + $code->price
            ]);

            // بحط اي فلوس الطالب ده شحنها عشان يعرف التوتال بتاعه كام
            $user->update([
                'wallet' => $user->wallet + $code->price
            ]);

            $code->update([
                'is_active' => 0
            ]);

        });

    }

    private function create_charge(Code $code, $user) {

        Charge::create([
            'price'      => $code->price,
            'code'       => $code->code,
            'teacher_id' => $code->teacher->id,
            'student_id' => $user->student->id
        ]);

    }

    private function create_wallet(Code $code, $user) {

        $wallet = Wallet::where('student_id', $user->student->id)->where('teacher_id', $code->teacher->id)->first();
        if($wallet) {

            $wallet->update([
                'price' => $wallet->price + $code->price
            ]);

        } else {

            Wallet::create([
                'price'      => $code->price,
                'teacher_id' => $code->teacher->id,
                'student_id' => $user->student->id
            ]);

        }

    }

    public function show_all_charges() {

        $user = auth()->user();
        if($user->type == "student") {

            $charges = Charge::with('teacher')->where('student_id', $user->student->id)->get();

        } elseif($user->type == "teacher") {

            $charges = Charge::with('student')->where('teacher_id', $user->teacher->id)->get();

        } else {

            $charges = Charge::with('teacher', 'student')->get();

        }

        if(count($charges) > 0) {

            return ChargeResource::collection($charges);

        } else {

            throw new Exception('Not Found');

        }

    }

    public function show_all_wallets() {

        $user = auth()->user();
        if($user->type == "student") {

            $wallets = Wallet::with('teacher')->where('student_id', $user->student->id)->get();

        } elseif($user->type == "teacher") {

            $wallets = Wallet::with('student')->where('teacher_id', $user->teacher->id)->get();

        } else {

            $wallets = Wallet::with('teacher', 'student')->get();

        }

        if(count($wallets) > 0) {

            return WalletResource::collection($wallets);

        } else {

            throw new Exception('Not Found');

        }

    }
}
