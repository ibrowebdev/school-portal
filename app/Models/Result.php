<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'school_class_id',
        'term_id',
        'academic_session_id',
        'ca_score',
        'exam_score',
        'total_score',
        'grade',
        'remark',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'ca_score' => 'decimal:2',
            'exam_score' => 'decimal:2',
            'total_score' => 'decimal:2',
        ];
    }

    // ─── Auto-compute total, grade, and remark ─────────────────

    protected static function booted(): void
    {
        $computeGrade = function (Result $result) {
            $result->total_score = $result->ca_score + $result->exam_score;

            $gradeSetting = GradeSetting::where('min_score', '<=', $result->total_score)
                ->where('max_score', '>=', $result->total_score)
                ->first();

            if ($gradeSetting) {
                $result->grade = $gradeSetting->grade;
                $result->remark = $gradeSetting->remark;
            }
        };

        static::creating($computeGrade);
        static::updating($computeGrade);
    }

    // ─── Relationships ─────────────────────────────────────────

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
