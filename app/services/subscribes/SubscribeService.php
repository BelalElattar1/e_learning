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
        if($subscribe) {

            $subscribe->update([
                'start'                => $request['status'] == 'active' ? Carbon::now()->format("Y-m-d") : NULL,
                'end'                  => $request['status'] == 'active' ?  Carbon::now()->addMonth()->format("Y-m-d") : NULL,
                'status'               => $request['status'],
                'reason_for_rejection' => $request['status'] == 'rejected' ?  $request['reason_for_rejection'] : NULL
            ]);

        } else {

            throw new Exception('This subscription has been modified in the past.');

        }

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