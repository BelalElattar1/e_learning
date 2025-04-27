<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Services\reports\ReportService;

class ReportController extends Controller
{
    use ResponseTrait;
    
    protected $report_service;

    public function __construct(ReportService $report_service)
    {
        $this->report_service = $report_service;
    }

    public function owner_and_admin() {

        try {

            $data = $this->report_service->owner_and_admin();
            return $this->response('All reports were successfully fetched.', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
