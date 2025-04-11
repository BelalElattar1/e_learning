<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Models\StudentExam;
use App\Services\degrees\DegreeService;

class DegreeController extends Controller
{
    use ResponseTrait;
    
    protected $degree_service;

    public function __construct(DegreeService $degree_service)
    {
        $this->degree_service = $degree_service;
    }

    public function show_all_degrees() {

        try {

            $data = $this->degree_service->show_all_degrees();
            return $this->response('All your grades have been successfully fetched.', 201, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function show_exam_answers(StudentExam $exam) {

        try {

            $data = $this->degree_service->show_exam_answers($exam);
            return $this->response('All your Answers have been successfully fetched.', 201, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
