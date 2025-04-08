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
            $this->update_teacher_wallet($code);
            $this->update_user_wallet($code, $user);
            $code->update(['is_active' => 0]);

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

        $wallet = Wallet::firstOrCreate([
            'student_id' => $user->student->id, 
            'teacher_id' => $code->teacher->id
        ]);
        $wallet->increment('price', $code->price);

    }

    private function update_teacher_wallet(Code $code) {
        $code->teacher->user->increment('wallet', $code->price);
    }
    
    private function update_user_wallet(Code $code, $user) {
        $user->increment('wallet', $code->price);
    }

    public function show_all_charges() {

        $user = auth()->user();
        $query = Charge::select('price', 'code', 'student_id', 'teacher_id', 'created_at');
        
        if ($user->type == "student") {
            $query->where('student_id', $user->student->id)->with(['teacher:id,user_id', 'teacher.user:id,name']);
        } elseif ($user->type == "teacher") {
            $query->where('teacher_id', $user->teacher->id)->with(['student:id,user_id', 'student:user:id,name']);
        } else {

            $query->with([
                'teacher:id,user_id', 
                'teacher.user:id,name',
                'student:id,user_id',
                'student:user:id,name'
            ]);

        }

        $charges = $query->get();
        abort_if($charges->isEmpty(), 404, 'Not Found');
        return ChargeResource::collection($charges);

    }

    public function show_all_wallets() {

        $user = auth()->user();
        $query = Wallet::select('price', 'student_id', 'teacher_id', 'created_at');
        
        if ($user->type == "student") {
            $query->where('student_id', $user->student->id)->with(['teacher:id,user_id', 'teacher.user:id,name']);
        } elseif ($user->type == "teacher") {
            $query->where('teacher_id', $user->teacher->id)->with(['student:id,user_id', 'student:user:id,name']);
        } else {

            $query->with([
                'teacher:id,user_id', 
                'teacher.user:id,name',
                'student:id,user_id',
                'student:user:id,name'
            ]);

        }

        $wallets = $query->get();
        abort_if($wallets->isEmpty(), 404, 'Not Found');
        return WalletResource::collection($wallets);

    }
}
