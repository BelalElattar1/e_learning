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

            // Manage Materials
            'create_material',
            'update_material',
            'delete_material',
            
        ];

        foreach($permissions as $permission) {

            Permission::create([
                'name' => $permission
            ]);

        }
        
    }
}
