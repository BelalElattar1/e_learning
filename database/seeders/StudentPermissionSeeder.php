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

            // Manage Admins
            'show_all_admins',
            
        ];

        $role = Role::findByName('student');
        $role->syncPermissions($permissions);

    }
}
