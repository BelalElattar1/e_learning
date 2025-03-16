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
            
        ];

        $role = Role::findByName('teacher');
        $role->syncPermissions($permissions);

    }
}
