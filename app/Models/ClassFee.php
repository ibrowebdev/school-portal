<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'academic_session_id',
        'term_id',
        'fees_type_id',
        'amount',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function feesType(): BelongsTo
    {
        return $this->belongsTo(FeesType::class);
    }
}
