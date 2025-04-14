<?php

namespace App\Services\materials;

use Exception;
use App\Models\Material;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class MaterialService
{

    public function index($id = null) {

        try {

            JWTAuth::parseToken()->authenticate();
            $user = auth()->user();
    
            return match ($user->type) {
                'student' => Material::where('academic_year_id', $user->student->academic_year_id)->pluck('name'),
                default   => Material::pluck('name')
            };
    
        } catch (JWTException $e) {

            if (!is_null($id)) {
                return Material::where('academic_year_id', $id)->pluck('name');
            }
        
            return Material::pluck('name');

        }

    }

    public function store($request) {
        
        Material::create([
            ...$request->only(['name', 'academic_year_id'])
        ]);

    }

    public function update($request, Material $material) {
        
       $material->update([
            ...$request->only(['name', 'academic_year_id'])
        ]);

    }

    public function destroy(Material $material) {

        $material->delete();

    }

}
