<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\questions\QuestionService;
use App\Http\Requests\questions\QuestionRequest;
use App\Models\Question;

class QuestionController extends Controller
{
    use ResponseTrait;
    
    protected $question_service;

    public function __construct(QuestionService $question_service)
    {
        $this->question_service = $question_service;
    }

    public function store(QuestionRequest $request) {

        try {

            $this->question_service->store($request);
            return $this->response('Question created successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(Question $question) {

        try {

            $this->question_service->destroy($question);
            return $this->response('Question Deleted successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
