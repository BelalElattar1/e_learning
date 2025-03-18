<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions = [

            // Manage Admins
            'show_all_admins',
            'create_admin',
            'update_admin',
            'delete_admin',

            // Manage Teachers
            'create_teacher',
            'update_teacher',
            'delete_teacher',

            // Manage User
            'get_all_students_inactive',
            'student_activation',
            'get_private_image',


            // Manage Subscribes
            'show_all_subscribes',
            'create_subscribe',
            'update_subscription_status',
            'delete_subscribe',

            // Manage Courses
            'creat_course',
            'update_course',

            // Manage Codes
            'create_code',

            // Manage Charges
            'charge',
            'show_all_charges',
            'show_all_wallets',

            // Manage buyings
            'buying',
            'my_courses',

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

            // Manage Answers
            'answer',

            // Manage Degrees
            'show_all_degrees',
            'show_details_degree'
            
        ];

        foreach($permissions as $permission) {

            Permission::create([
                'name' => $permission
            ]);

        }
        
    }
}
