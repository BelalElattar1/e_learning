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
            
        ];

        $role = Role::findByName('teacher');
        $role->syncPermissions($permissions);

    }
}
