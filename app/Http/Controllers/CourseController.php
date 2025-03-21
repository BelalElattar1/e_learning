<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Course;
use App\ResponseTrait;
use App\services\courses\CourseService;
use App\Http\Requests\courses\CourseRequest;

class CourseController extends Controller
{
    use ResponseTrait;

    protected $course_service;

    public function __construct(CourseService $course_service)
    {
        $this->course_service = $course_service;
    }

    public function show(Course $course) {

        try {

            $data = $this->course_service->show($course);
            return $this->response('Show Details Course Suc', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function index() {

        try {

            $data = $this->course_service->index();
            return $this->response('All courses have been brought', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function store(CourseRequest $request) {

        try {

            $this->course_service->store($request->all());
            return $this->response('The course has been created successfully.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
        
    }

    public function update(CourseRequest $request, Course $course) {

        try {


            $this->course_service->update($request->all(), $course);
            return $this->response('The course has been Updated successfully.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
        
    }

}
