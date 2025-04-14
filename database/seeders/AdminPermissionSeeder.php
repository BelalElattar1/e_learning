<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [

            // Manage Admins
            'show_all_admins',

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
            'show_exam_answers'
            
        ];

        $admin = User::create([
            'name'       => 'ahmed',
            'email'      => 'ahmed@gmail.com',
            'password'   => Hash::make('password'),
            'type'       => 'admin',
            'is_active' => 1
        ]);

        $role = Role::findByName('admin');
        $role->syncPermissions($permissions);
        $admin->assignRole('admin');

    }
}
