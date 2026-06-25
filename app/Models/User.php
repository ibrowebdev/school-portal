<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    const STUDENT = 'student';
    const TEACHER = 'teacher';
    const PARENT = 'parent';
    const ADMIN = 'admin';
    const SUPER_ADMIN = 'super-admin';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'type',
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

    /**
     * Auto-generate a prefixed user_id on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (User $model) {
            if (empty($model->user_id)) {
                $latest = static::orderByDesc('user_id')->value('user_id');
                $nextID = $latest ? intval(substr($latest, 3)) + 1 : 1;
                $model->user_id = '000' . sprintf('%03d', $nextID);
            }
        });
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

    // ─── Relationships ──────────────────────────────────────

    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }
}
