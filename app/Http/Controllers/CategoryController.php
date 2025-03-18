<?php

namespace App\Http\Controllers;

use Exception;
use App\ResponseTrait;
use App\Models\Category;
use App\Services\categories\CategoryService;
use App\Http\Requests\categories\StoreCategoryRequest;
use App\Http\Requests\categories\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use ResponseTrait;

    protected $category_service;

    public function __construct(CategoryService $category_service)
    {
        $this->category_service = $category_service;
    }

    public function store(StoreCategoryRequest $request) {

        try {

            $this->category_service->store($request);
            return $this->response('The Category has been created successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function update(UpdateCategoryRequest $request, Category $category) {

        try {

            $this->category_service->update($request, $category);
            return $this->response('The Category has been Updated successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

    public function destroy(Category $category) {

        try {

            $this->category_service->destroy($category);
            return $this->response('The Category has been Deleted successfully');

        } catch(Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }
}
