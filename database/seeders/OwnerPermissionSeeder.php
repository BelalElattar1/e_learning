<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OwnerPermissionSeeder extends Seeder
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

            // Manage Materials
            'create_material',
            'update_material',
            'delete_material',

            // Manage User
            'get_all_students_inactive',
            'get_all_students',
            'student_activation',

            // Manage Subscribes
            'show_all_subscribes',
            'update_subscription_status',
            'delete_subscribe',
            'filter_subscribe',

            // Manage Charges
            'show_all_charges',
            'show_all_wallets',

            // Manage Degrees
            'show_all_degrees',
            'show_exam_answers',
            
            // Manage Reports
            'owner_admin_reports'
            
        ];

        $owner = User::create([
            'name'       => 'belal',
            'email'      => 'belal@gmail.com',
            'password'   => Hash::make('password'),
            'type'       => 'owner',
            'is_active' => 1
        ]);

        $role = Role::findByName('owner');
        $role->syncPermissions($permissions);
        $owner->assignRole('owner');

        // $user->hasRole('admin')
        // $user->can('edit articles')
        // $permission->removeRole($role);
        // $role->revokePermissionTo($permission);

    }
}
