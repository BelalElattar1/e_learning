<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\charges\ChargeService;
use App\Http\Requests\charges\ChargeRequest;

class ChargeController extends Controller
{
    use ResponseTrait;

    protected $charge_service;

    public function __construct(ChargeService $charge_service)
    {
        $this->charge_service = $charge_service;
    }

    public function show_all_charges() {

        try {

            $data = $this->charge_service->show_all_charges();
            return $this->response('The code has been shipped successfully', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function show_all_wallets() {

        try {

            $data = $this->charge_service->show_all_wallets();
            return $this->response('The code has been shipped successfully', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function charge(ChargeRequest $request) {

        try {

            $this->charge_service->charge($request->all());
            return $this->response('The code has been shipped successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
