<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\answers\AnswerService;
use App\Http\Requests\answers\AnswerRequest;

class AnswerController extends Controller
{
    use ResponseTrait;
    
    protected $answer_service;

    public function __construct(AnswerService $answer_service)
    {
        $this->answer_service = $answer_service;
    }

    public function answer(AnswerRequest $request) {

        try {

            $this->answer_service->answer($request);
            return $this->response('The answers have been successfully registered. Please go to the dashboard to see your score and answers.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
