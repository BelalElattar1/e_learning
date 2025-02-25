<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Academicyear;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicyears = [
            'الصف الاول الثانوي',
            'الصف الثاني الثانوي',
            'الصف الثالث الثانوي'
        ];

        foreach($academicyears as $academicyear) {

            Academicyear::create([
                'name' => $academicyear
            ]);
        }
    }
}
