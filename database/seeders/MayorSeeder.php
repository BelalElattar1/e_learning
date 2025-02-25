<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mayor;

class MayorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mayors = [
            'اسكندرية',
            'اسماعيلية',
            'اسوان',
            'اسيوط',
            'الاقصر',
            'بحر الاحمر',
            'البحيرة',
            'بني سويف',
            'بورسعيد',
            'جنوب سينا',
            'جيزة',
            'دقهلية',
            'دمياط',
            'سوهاج',
            'السويس',
            'الشرقية',
            'شمال سيناء',
            'غربية',
            'فيوم',
            'قاهرة',
            'قليوبية',
            'قنا',
            'كفر الشيخ',
            'مطروح',
            'المنوفية',
            'المنيا',
            'الوادي الجديد'
        ];

        foreach($mayors as $mayor) {

            Mayor::create([
                'name' => $mayor
            ]);

        }
    }
}
