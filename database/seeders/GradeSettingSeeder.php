<?php

namespace Database\Seeders;

use App\Models\GradeSetting;
use Illuminate\Database\Seeder;

class GradeSettingSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['min_score' => 70, 'max_score' => 100, 'grade' => 'A', 'remark' => 'Excellent'],
            ['min_score' => 60, 'max_score' => 69,  'grade' => 'B', 'remark' => 'Very Good'],
            ['min_score' => 50, 'max_score' => 59,  'grade' => 'C', 'remark' => 'Good'],
            ['min_score' => 45, 'max_score' => 49,  'grade' => 'D', 'remark' => 'Fair'],
            ['min_score' => 40, 'max_score' => 44,  'grade' => 'E', 'remark' => 'Poor'],
            ['min_score' => 0,  'max_score' => 39,  'grade' => 'F', 'remark' => 'Fail'],
        ];

        foreach ($grades as $grade) {
            GradeSetting::firstOrCreate(
                ['grade' => $grade['grade']],
                $grade
            );
        }
    }
}
