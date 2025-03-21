<?php

namespace App\services\subscribes;

use Exception;
use Carbon\Carbon;
use App\Models\Subscribe;
use App\Http\Resources\SubscribeResource;

class SubscribeService {

    public function index() {

        $user = auth()->user();
        if($user->type == 'teacher') {

            $subscribes = Subscribe::where('teacher_id', $user->teacher->id)->get();
            return SubscribeResource::collection($subscribes);

        } else {

            $subscribes = Subscribe::with('teacher')->get();
            return SubscribeResource::collection($subscribes);

        }

    }

    public function store($request) {

        $user = auth()->user();
        Subscribe::create([
            'pay_photo'  => store_image($request['pay_photo'], 'subscribes'),
            'teacher_id' => $user->teacher->id
        ]);

    }

    public function update_subscription_status($request, Subscribe $subscribe) {

        $subscribe = Subscribe::where('id', $subscribe->id)->where('status', 'pending')->first();

        if (!$subscribe) {
            throw new Exception('This subscription has been modified in the past.');
        }

        $is_subscribed = Subscribe::where('teacher_id', $subscribe->teacher_id)
                        ->where('status', 'active')
                        ->latest('id') 
                        ->first();

        $dates = $this->calculate_subscribtion_date($request['status'], $is_subscribed);

        $subscribe->update([
            'start'                => $dates['start'],
            'end'                  => $dates['end'],
            'status'               => $request['status'],
            
            'reason_for_rejection' => $request['status'] == 'rejected' 
                                    ? $request['reason_for_rejection'] 
                                    : null,
        ]);

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

    public function destroy(Subscribe $subscribe) {

        $subscribe = Subscribe::where('id', $subscribe->id)->where('status', 'pending')->first();
        if($subscribe) {

            $subscribe->delete();

        } else {

            throw new Exception('This subscription is not eligible for deletion.');

        }

    }

}