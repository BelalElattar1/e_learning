<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            'عربي',
            'الماني',
            'فرنساوي',
            'انجليزي',
            'احياء',
            'فلسفة ومنطق',
            'علم نفس واجتماع',
            'كيمياء',
            'فيزياء',
            'رياضيات',
            'تاريخ',
            'ايطالي'
        ];

        foreach($materials as $material) {

            Material::create([
                'name'             => $material,
                'academic_year_id' => random_int(1, 3)
            ]);
        }
    }
}
