<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Models\Material;
use App\Services\materials\MaterialService;
use App\Http\Requests\materials\MaterialRequest;

class MaterialController extends Controller
{
    use ResponseTrait;
    
    protected $material_service;

    public function __construct(MaterialService $material_service)
    {
        $this->material_service = $material_service;
    }

    public function store(MaterialRequest $request) {

        try {

            $this->material_service->store($request);
            return $this->response('The material has been created successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function update(MaterialRequest $request, Material $material) {

        try {

            $this->material_service->update($request, $material);
            return $this->response('The material has been updated successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(Material $material) {

        try {

            $this->material_service->destroy($material);
            return $this->response('The material has been Deleted successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

}
