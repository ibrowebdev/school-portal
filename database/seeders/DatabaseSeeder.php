<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Term;
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
        // 1. Roles & Permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Subjects
        $this->call(SubjectSeeder::class);

        // 3. Grade Settings
        $this->call(GradeSettingSeeder::class);

        // ─── 4. Academic Session & Terms ────────────────────────
        $session = AcademicSession::firstOrCreate(
            ['name' => '2025/2026'],
            [
                'start_date' => '2025-09-01',
                'end_date' => '2026-07-31',
                'is_current' => true,
            ]
        );

        $terms = [
            ['name' => 'First Term', 'start_date' => '2025-09-01', 'end_date' => '2025-12-20', 'is_current' => true],
            ['name' => 'Second Term', 'start_date' => '2026-01-10', 'end_date' => '2026-04-15', 'is_current' => false],
            ['name' => 'Third Term', 'start_date' => '2026-04-25', 'end_date' => '2026-07-31', 'is_current' => false],
        ];

        foreach ($terms as $term) {
            Term::firstOrCreate(
                ['academic_session_id' => $session->id, 'name' => $term['name']],
                array_merge($term, ['academic_session_id' => $session->id])
            );
        }

        // ─── 5. School Classes & Sections ───────────────────────
        $classes = [
            ['name' => 'JSS 1', 'level' => 'junior', 'capacity' => 40],
            ['name' => 'JSS 2', 'level' => 'junior', 'capacity' => 40],
            ['name' => 'JSS 3', 'level' => 'junior', 'capacity' => 40],
            ['name' => 'SS 1', 'level' => 'senior', 'capacity' => 35],
            ['name' => 'SS 2', 'level' => 'senior', 'capacity' => 35],
            ['name' => 'SS 3', 'level' => 'senior', 'capacity' => 35],
        ];

        foreach ($classes as $classData) {
            $class = SchoolClass::firstOrCreate(
                ['name' => $classData['name']],
                $classData
            );

            // Create sections A, B for each class
            foreach (['A', 'B'] as $section) {
                ClassSection::firstOrCreate(
                    ['school_class_id' => $class->id, 'name' => $section]
                );
            }
        }

        // ─── 6. Map subjects to classes ─────────────────────────
        $allSubjects = \App\Models\Subject::all();
        $allClasses = SchoolClass::all();

        // Map core subjects (MTH, ENG, CVE) to all classes
        $coreSubjectCodes = ['MTH', 'ENG', 'CVE'];
        $coreSubjects = $allSubjects->whereIn('code', $coreSubjectCodes);

        foreach ($allClasses as $class) {
            $class->subjects()->syncWithoutDetaching($coreSubjects->pluck('id'));
        }

        // Map science subjects to SS classes
        $scienceSubjectCodes = ['PHY', 'CHM', 'BIO', 'FMT'];
        $scienceSubjects = $allSubjects->whereIn('code', $scienceSubjectCodes);
        $seniorClasses = $allClasses->where('level', 'senior');

        foreach ($seniorClasses as $class) {
            $class->subjects()->syncWithoutDetaching($scienceSubjects->pluck('id'));
        }

        // Map arts subjects to SS classes
        $artsSubjectCodes = ['LIT', 'GOV', 'ECO', 'HIS', 'GEO'];
        $artsSubjects = $allSubjects->whereIn('code', $artsSubjectCodes);

        foreach ($seniorClasses as $class) {
            $class->subjects()->syncWithoutDetaching($artsSubjects->pluck('id'));
        }

        // Map general subjects to JSS classes
        $jssSubjectCodes = ['BIO', 'PHY', 'CHM', 'AGR', 'CSC', 'HIS', 'GEO', 'CRS', 'FRN'];
        $jssSubjects = $allSubjects->whereIn('code', $jssSubjectCodes);
        $juniorClasses = $allClasses->where('level', 'junior');

        foreach ($juniorClasses as $class) {
            $class->subjects()->syncWithoutDetaching($jssSubjects->pluck('id'));
        }

        // ─── 7. Users ───────────────────────────────────────────

        // Super Admin
        $admin = User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::SUPER_ADMIN,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $admin->assignRole(User::SUPER_ADMIN);

        // Teacher
        $teacher = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Okafor',
            'email' => 'teacher@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::TEACHER,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $teacher->assignRole(User::TEACHER);

        TeacherProfile::firstOrCreate(
            ['user_id' => $teacher->id],
            [
                'employee_id' => 'TCH-001',
                'qualification' => 'B.Sc Mathematics',
                'experience' => '5 years',
                'address' => '12 Teacher Avenue',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
            ]
        );

        // Assign teacher to JSS 1 for Mathematics
        $jss1 = SchoolClass::where('name', 'JSS 1')->first();
        $maths = $allSubjects->where('code', 'MTH')->first();

        if ($jss1 && $maths) {
            $teacher->assignedClasses()->syncWithoutDetaching([
                $jss1->id => [
                    'subject_id' => $maths->id,
                    'academic_session_id' => $session->id,
                ],
            ]);
        }

        // Parent
        $parent = User::factory()->create([
            'first_name' => 'Mrs. Amina',
            'last_name' => 'Bello',
            'email' => 'parent@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::PARENT,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $parent->assignRole(User::PARENT);

        // Student (linked to parent)
        $student = User::factory()->create([
            'first_name' => 'Ibrahim',
            'last_name' => 'Bello',
            'email' => 'student@school-portal.test',
            'password' => Hash::make('password'),
            'type' => User::STUDENT,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        $student->assignRole(User::STUDENT);

        $jss1Section = ClassSection::where('school_class_id', $jss1?->id)
            ->where('name', 'A')
            ->first();

        StudentProfile::firstOrCreate(
            ['user_id' => $student->id],
            [
                'admission_id' => 'ADM-2025-001',
                'roll_number' => '001',
                'school_class_id' => $jss1?->id,
                'class_section_id' => $jss1Section?->id,
                'blood_group' => 'O+',
                'religion' => 'Islam',
                'address' => '5 Student Lane, Abuja',
                'parent_id' => $parent->id,
            ]
        );
    }
}
