<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TeacherPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions = [

            // Manage Subscribes
            'show_all_subscribes',
            'create_subscribe',
            'delete_subscribe',

            // Manage Courses
            'creat_course',
            'update_course',

            // Manage Codes
            'create_code',

            // Manage Charges
            'show_all_charges',
            'show_all_wallets',

            // Manage Categories
            'create_category',
            'update_category',
            'delete_category',

            // Manage Sections
            'create_section',
            'update_section',
            'delete_section',

            // Manage Questions
            'create_question',
            'update_question',
            'delete_question',

            // Manage Degrees
            'show_all_degrees',
            'show_details_degree'
            
        ];

        $role = Role::findByName('teacher');
        $role->syncPermissions($permissions);

    }
}
