<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StudentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions = [

            // Manage Charges
            'charge',
            'show_all_charges',
            'show_all_wallets',

            // Manage buyings
            'buying',
            'my_courses',

            // Manage Answers
            'answer',

            // Manage Degrees
            'show_all_degrees',
            'show_exam_answers',

            // Manage Reports
            'student_reports',

            // Manage View
            'view_lecture'
            
        ];

        $role = Role::findByName('student');
        $role->syncPermissions($permissions);

    }
}
