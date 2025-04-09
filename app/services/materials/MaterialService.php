<?php

namespace App\Services\materials;

use Exception;
use App\Models\Material;

class MaterialService
{

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
