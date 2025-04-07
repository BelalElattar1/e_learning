<?php

namespace App\Services\materials;

use Exception;
use App\Models\Material;

class MaterialService
{

    public function store($request) {
        
        Material::create([
            'name'             => $request['name'],
            'academic_year_id' => $request['academic_year_id']
        ]);

    }

    public function update($request, Material $material) {
        
       $material->update([
            'name' => $request['name']
        ]);

    }

    public function destroy(Material $material) {

        $material->delete();

    }

}
