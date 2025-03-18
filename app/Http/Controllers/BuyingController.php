<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Course;
use App\ResponseTrait;
use App\Services\buyings\BuyingService;
use App\Http\Requests\buyings\BuyingRequest;

class BuyingController extends Controller
{
    use ResponseTrait;

    protected $buying_service;

    public function __construct(BuyingService $buying_service)
    {
        $this->buying_service = $buying_service;
    }

    public function my_courses() {

        try {

            $data = $this->buying_service->my_courses();
            return $this->response('Show All Your Courses Successfully.', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function buying(Course $course) {

        try {

            $this->buying_service->buying($course);
            return $this->response('The course has been successfully purchased.');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
