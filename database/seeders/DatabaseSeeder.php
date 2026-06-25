<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a default super-admin user
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::SUPER_ADMIN,
            'status' => 'Active',
        ]);
        $admin->assignRole(User::SUPER_ADMIN);

        // Create a test teacher
        $teacher = User::factory()->create([
            'name' => 'John Teacher',
            'email' => 'teacher@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::TEACHER,
            'status' => 'Active',
        ]);
        $teacher->assignRole(User::TEACHER);

        // Create a test student
        $student = User::factory()->create([
            'name' => 'Jane Student',
            'email' => 'student@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::STUDENT,
            'status' => 'Active',
        ]);
        $student->assignRole(User::STUDENT);
    }
}
