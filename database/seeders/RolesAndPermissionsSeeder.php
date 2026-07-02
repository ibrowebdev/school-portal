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
            // Core management
            'manage-students',
            'manage-teachers',
            'manage-departments',
            'manage-subjects',
            'manage-invoices',
            'manage-fees',
            'manage-users',
            'view-dashboard',
            'view-reports',

            // Academic management
            'manage-academic-sessions',
            'manage-classes',
            'manage-class-subjects',
            'manage-class-teachers',

            // Results
            'upload-results',
            'view-results',
            'manage-grade-settings',

            // Parent
            'view-children',

            // Attendance
            'manage-attendance',
            'view-attendance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── Super Admin: ALL permissions ───────────────────────
        $superAdmin = Role::firstOrCreate(['name' => User::SUPER_ADMIN]);
        $superAdmin->syncPermissions(Permission::all());

        // ─── Admin: All except user management ──────────────────
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
            'manage-academic-sessions',
            'manage-classes',
            'manage-class-subjects',
            'manage-class-teachers',
            'upload-results',
            'view-results',
            'manage-grade-settings',
            'manage-attendance',
            'view-attendance',
        ]);

        // ─── Teacher: View dashboard, results, attendance ───────
        $teacher = Role::firstOrCreate(['name' => User::TEACHER]);
        $teacher->syncPermissions([
            'view-dashboard',
            'view-reports',
            'view-results',
            'manage-attendance',
            'view-attendance',
        ]);

        // ─── Student: View dashboard and own results ────────────
        $student = Role::firstOrCreate(['name' => User::STUDENT]);
        $student->syncPermissions([
            'view-dashboard',
            'view-results',
            'view-attendance',
        ]);

        // ─── Parent: View dashboard, children, and results ──────
        $parent = Role::firstOrCreate(['name' => User::PARENT]);
        $parent->syncPermissions([
            'view-dashboard',
            'view-children',
            'view-results',
            'view-attendance',
        ]);
    }
}
