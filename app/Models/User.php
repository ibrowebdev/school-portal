<?php

namespace App\Models;

use App\Observers\UserObserver;
use App\Traits\HasMediaTrait;
use App\Traits\InteractWithUserAttributes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasMediaTrait, InteractWithUserAttributes, SoftDeletes;

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

}
