<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OwnerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::create(['name' => 'owner']);
        $permissions = [

            // Manage Admins
            'show_all_admins',
            'create_admin',
            'update_admin',
            'delete_admin',

            // Manage Teachers
            'show_all_teachers',
            'create_teacher',
            'update_teacher',
            'delete_teacher',

            // Manage Materials
            'show_all_materials',
            'create_material',
            'update_material',
            'delete_material',
            
        ];

        foreach($permissions as $permission) {

            Permission::create([
                'name' => $permission
            ]);

        }

        $role->syncPermissions($permissions);
        $user = User::create([
            'name'     => '',
            'email'    => '',
            'password' => Hash::make('password'),
            'type'     => 'owner'
        ]);

        $user->assignRole('owner');

        // $user->hasRole('admin')
        // $user->can('edit articles')
        // $permission->removeRole($role);
        // $role->revokePermissionTo($permission);

    }
}
