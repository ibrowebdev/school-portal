<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-users',
            'manage-students',
            'manage-teachers',
            'manage-departments',
            'manage-subjects',
            'manage-academic',
            'manage-invoices',
            'manage-fees',
            'manage-settings',
            'manage-grade-settings',
            'upload-results',
            'view-results',
            'mark-attendance',
            'view-attendance-report',
            'view-children',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin gets all permissions implicitly or explicitly
        $superAdmin = Role::firstOrCreate(['name' => User::SUPER_ADMIN]);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin gets all permissions
        $admin = Role::firstOrCreate(['name' => User::ADMIN]);
        $admin->givePermissionTo(Permission::all());

        // Teacher
        $teacher = Role::firstOrCreate(['name' => User::TEACHER]);
        $teacher->givePermissionTo([
            'view-results',
            'upload-results',
            'mark-attendance',
            'view-attendance-report',
        ]);

        // Student
        $student = Role::firstOrCreate(['name' => User::STUDENT]);
        $student->givePermissionTo([
            'view-results',
            'view-attendance-report',
        ]);

        // Parent
        $parent = Role::firstOrCreate(['name' => User::PARENT]);
        $parent->givePermissionTo([
            'view-children',
            'view-results',
            'view-attendance-report',
        ]);
    }
}
