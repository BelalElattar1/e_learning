<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\services\subscribes\SubscribeService;

class SubscribeController extends Controller
{
    use ResponseTrait;

    protected $subscribe_service;

    public function __construct(SubscribeService $subscribe_service)
    {
        $this->subscribe_service = $subscribe_service;
    }

    public function index() {

        try {

            $data = $this->subscribe_service->index();
            return $this->response('Show All Subscribes', 201, $data);


        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'pay_photo' => ['required', 'file', 'max:1048576', 'mimes:jpg,jpeg,png', 'unique:subscribes']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {

            $this->subscribe_service->store($request->all());
            return $this->response('Your subscription has been successfully registered. Please wait for approval.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function update_subscription_status(Request $request, Subscribe $subscribe) {

        $validator = Validator::make($request->all(), [
            'status'               => ['required', 'in:active,rejected'],
            'reason_for_rejection' => ['required_if:status,rejected', 'string', 'min:5', 'max:255']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {

            $this->subscribe_service->update_subscription_status($request->all(), $subscribe);
            return $this->response('Subscription status updated successfully');
            
        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(Subscribe $subscribe) {

        try {

            $this->subscribe_service->destroy($subscribe);
            return $this->response('The subscription has been successfully deleted');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
