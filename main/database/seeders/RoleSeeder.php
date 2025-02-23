<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::create(['name' => 'customer']);
        $permissions = [
            'show_all_product',
            'show_details_product',
        ];

        foreach($permissions as $permission) {

            Permission::create([
                'name' => $permission
            ]);

        }

        $role->syncPermissions($permissions);
        
        // $role->revokePermissionTo($permission);
        // $permission->removeRole($role);

    }
}

