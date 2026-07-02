<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->decimal('ca_score', 5, 2)->default(0);         // Continuous Assessment (max 40)
            $table->decimal('exam_score', 5, 2)->default(0);        // Exam score (max 60)
            $table->decimal('total_score', 5, 2)->default(0);       // CA + Exam (auto-computed)
            $table->string('grade')->nullable();                     // Auto-computed: A, B, C, D, E, F
            $table->string('remark')->nullable();                    // "Excellent", "Very Good", etc.
            $table->foreignId('uploaded_by')->constrained('users');   // admin who uploaded
            $table->timestamps();

            $table->unique(
                ['student_id', 'subject_id', 'term_id', 'academic_session_id'],
                'result_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
