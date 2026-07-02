<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MTH', 'description' => 'Study of numbers, quantities, and shapes'],
            ['name' => 'English Language', 'code' => 'ENG', 'description' => 'Study of English grammar, comprehension, and writing'],
            ['name' => 'Physics', 'code' => 'PHY', 'description' => 'Study of matter, energy, and fundamental forces'],
            ['name' => 'Chemistry', 'code' => 'CHM', 'description' => 'Study of substances and their reactions'],
            ['name' => 'Biology', 'code' => 'BIO', 'description' => 'Study of living organisms'],
            ['name' => 'Economics', 'code' => 'ECO', 'description' => 'Study of production, distribution, and consumption of goods'],
            ['name' => 'Government', 'code' => 'GOV', 'description' => 'Study of political systems and governance'],
            ['name' => 'Literature in English', 'code' => 'LIT', 'description' => 'Study of literary works in English'],
            ['name' => 'Agricultural Science', 'code' => 'AGR', 'description' => 'Study of farming and food production'],
            ['name' => 'Computer Science', 'code' => 'CSC', 'description' => 'Study of computation and information technology'],
            ['name' => 'Civic Education', 'code' => 'CVE', 'description' => 'Study of citizenship, rights, and responsibilities'],
            ['name' => 'History', 'code' => 'HIS', 'description' => 'Study of past events and civilizations'],
            ['name' => 'Geography', 'code' => 'GEO', 'description' => 'Study of places and relationships between people and environments'],
            ['name' => 'Further Mathematics', 'code' => 'FMT', 'description' => 'Advanced mathematical concepts'],
            ['name' => 'Technical Drawing', 'code' => 'TDR', 'description' => 'Engineering and architectural drawing'],
            ['name' => 'French', 'code' => 'FRN', 'description' => 'Study of the French language'],
            ['name' => 'Islamic Religious Studies', 'code' => 'IRS', 'description' => 'Study of Islamic religion and ethics'],
            ['name' => 'Christian Religious Studies', 'code' => 'CRS', 'description' => 'Study of Christian religion and ethics'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
