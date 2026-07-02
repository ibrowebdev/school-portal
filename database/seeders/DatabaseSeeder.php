<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
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
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::SUPER_ADMIN,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $admin->assignRole(User::SUPER_ADMIN);

        // Create a test teacher
        $teacher = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Teacher',
            'email' => 'teacher@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::TEACHER,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $teacher->assignRole(User::TEACHER);

        // Create a test student
        $student = User::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Student',
            'email' => 'student@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::STUDENT,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $student->assignRole(User::STUDENT);
    }
}
