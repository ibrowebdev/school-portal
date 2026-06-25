<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-students',
            'manage-teachers',
            'manage-departments',
            'manage-subjects',
            'manage-invoices',
            'manage-fees',
            'manage-users',
            'view-dashboard',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => User::SUPER_ADMIN]);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => User::ADMIN]);
        $admin->syncPermissions([
            'manage-students',
            'manage-teachers',
            'manage-departments',
            'manage-subjects',
            'manage-invoices',
            'manage-fees',
            'manage-users',
            'view-dashboard',
            'view-reports',
        ]);

        $teacher = Role::firstOrCreate(['name' => User::TEACHER]);
        $teacher->syncPermissions([
            'view-dashboard',
            'view-reports',
        ]);

        $student = Role::firstOrCreate(['name' => User::STUDENT]);
        $student->syncPermissions([
            'view-dashboard',
        ]);

        Role::firstOrCreate(['name' => User::PARENT]);
    }
}
