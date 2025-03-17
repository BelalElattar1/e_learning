<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\codes\CodeService;
use App\Http\Requests\codes\CodeRequest;

class CodeController extends Controller
{    
    use ResponseTrait;

    protected $code_service;

    public function __construct(CodeService $code_service)
    {
        $this->code_service = $code_service;
    }

    public function store(CodeRequest $request) {

        try {

            $data = $this->code_service->store($request->all());
            return $this->response('The Code has been created successfullyc', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }
    
}
