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
            'my_courses'
            
        ];

        $role = Role::findByName('student');
        $role->syncPermissions($permissions);

    }
}
