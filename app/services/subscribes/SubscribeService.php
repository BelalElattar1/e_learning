<?php

namespace App\services\subscribes;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscribe;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SubscribeResource;

class SubscribeService {

    public function filter($status) {

        $user = auth()->user();
    
        $query = Subscribe::with(['teacher:id,user_id', 'teacher.user:id,name'])->where('status', $status);
    
        if ($user->type == 'teacher') {
            $query->where('teacher_id', $user->teacher->id);
        }
    
        $subscribes = $query->get();
        abort_if($subscribes->isEmpty(), 404, 'Not Found');

        return SubscribeResource::collection($subscribes);
        
    }
    

    public function index() {

        $user = auth()->user();
        
        $query = Subscribe::with('teacher:id,user_id', 'teacher.user:id,name');

        if ($user->type == 'teacher') {
            $query->where('teacher_id', $user->teacher->id);
        }

        $subscribes = $query->get();
        abort_if($subscribes->isEmpty(), 404, 'Not Found');

        return SubscribeResource::collection($subscribes);

    }

    public function store($request) {

        $user = auth()->user();
        Subscribe::create([
            'pay_photo'  => store_image($request['pay_photo'], 'subscribes'),
            'teacher_id' => $user->teacher->id
        ]);

    }

    public function update_subscription_status($request, Subscribe $subscribe) {

        abort_if($subscribe->status !== 'pending', 404, 'This subscription has been modified in the past.');

        $is_subscribed = Subscribe::where('teacher_id', $subscribe->teacher_id)
                        ->where('status', 'active')
                        ->latest('id') 
                        ->first();

        $dates = $this->calculate_subscribtion_date($request['status'], $is_subscribed);

        DB::transaction(function () use ($dates, $request, $subscribe) {  

            $subscribe->update([
                'start'                => $dates['start'],
                'end'                  => $dates['end'],
                'status'               => $request['status'],
                'reason_for_rejection' => $request['status'] == 'rejected' ? $request['reason_for_rejection'] : null
            ]);

            if($request['status'] === 'active') {

                $this->update_owner_wallet();

            }

        });

    }

    private function calculate_subscribtion_date($status, $is_subscribed) {

        if ($status !== 'active') {

            return [
                'start' => null, 
                'end' => null
            ];

        }

        if (!$is_subscribed) {

            return [
                'start' => Carbon::now()->format("Y-m-d"),
                'end'   => Carbon::now()->addMonth()->format("Y-m-d")
            ];

        }
    
        return [
            'start' => Carbon::parse($is_subscribed->end)->addDay()->format("Y-m-d"), 
            'end'   => Carbon::parse($is_subscribed->end)->addMonth()->addDay()->format("Y-m-d")
        ];

    }

    private function update_owner_wallet() {
        User::where('id', 1)->where('type', 'owner')->increment('wallet', 150);
    }

    public function destroy(Subscribe $subscribe) {

        abort_if($subscribe->status !== 'pending', 404, 'This subscription is not eligible for deletion.');
        $subscribe->delete();

    }

}