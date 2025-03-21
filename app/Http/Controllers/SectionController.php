<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Models\Section;
use App\Services\sections\SectionService;
use App\Http\Requests\sections\StoreSectionRequest;
use App\Http\Requests\sections\UpdateSectionRequest;

class SectionController extends Controller
{
    use ResponseTrait;
    
    protected $section_service;

    public function __construct(SectionService $section_service)
    {
        $this->section_service = $section_service;
    }

    public function show(Section $section) {

        try {

            $data = $this->section_service->show($section);
            return $this->response('Show Section successfully', 200, $data);

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function store(StoreSectionRequest $request) {

        try {

            $this->section_service->store($request);
            return $this->response('The Section has been created successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function update(UpdateSectionRequest $request, Section $section) {

        try {

            $this->section_service->update($request, $section);
            return $this->response('The Section has been Updated successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(Section $section) {

        try {

            $this->section_service->destroy($section);
            return $this->response('The Section has been Deleted successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
