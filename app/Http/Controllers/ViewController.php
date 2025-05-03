<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\views\ViewService;
use App\Http\Requests\views\ViewRequest;

class ViewController extends Controller
{
    use ResponseTrait;
    
    protected $view_service;

    public function __construct(ViewService $view_service)
    {
        $this->view_service = $view_service;
    }

    public function store(ViewRequest $request) {

        try {

            $this->view_service->store($request);
            return $this->response('The lecture viewing has been successfully recorded.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
