<?php

namespace App\Models;

use App\Observers\UserObserver;
use App\Traits\HasMediaTrait;
use App\Traits\InteractWithUserAttributes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory, HasMediaTrait, HasRoles, InteractWithUserAttributes, Notifiable, SoftDeletes;

    const STUDENT = 'student';

    const TEACHER = 'teacher';

    const PARENT = 'parent';

    const ADMIN = 'admin';

    const SUPER_ADMIN = 'super-admin';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'name',
        'email',
        'type',
        'gender',
        'join_date',
        'date_of_birth',
        'phone_number',
        'status',
        'avatar',
        'position',
        'department',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Type Helpers ───────────────────────────────────────

    public function isStudent(): bool
    {
        return $this->type === self::STUDENT;
    }

    public function isTeacher(): bool
    {
        return $this->type === self::TEACHER;
    }

    public function isParent(): bool
    {
        return $this->type === self::PARENT;
    }

    public function isAdmin(): bool
    {
        return $this->type === self::ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->type === self::SUPER_ADMIN;
    }

    // ─── Profile Relationships ──────────────────────────────

    /**
     * Student-specific profile data (class, section, admission, parent link).
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Teacher-specific profile data (qualification, experience, etc.).
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    // ─── Parent ↔ Children ──────────────────────────────────

    /**
     * For parent users: get all linked children (students).
     */
    public function children(): HasMany
    {
        return $this->hasMany(StudentProfile::class, 'parent_id');
    }

    // ─── Results ────────────────────────────────────────────

    /**
     * For student users: get all results.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    // ─── Teacher Class Assignment ───────────────────────────

    /**
     * For teacher users: classes they are assigned to teach.
     */
    public function assignedClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_teacher')
            ->withPivot(['subject_id', 'academic_session_id'])
            ->withTimestamps();
    }

    // ─── Attendance ─────────────────────────────────────────

    /**
     * For student users: attendance records.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    // ─── Scopes ─────────────────────────────────────────────

    public function scopeStudents($query)
    {
        return $query->where('type', self::STUDENT);
    }

    public function scopeTeachers($query)
    {
        return $query->where('type', self::TEACHER);
    }

    public function scopeParents($query)
    {
        return $query->where('type', self::PARENT);
    }
}
