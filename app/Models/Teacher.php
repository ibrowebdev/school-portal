<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'user_id',
        'full_name',
        'gender',
        'date_of_birth',
        'mobile',
        'joining_date',
        'qualification',
        'experience',
        'username',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
        ];
    }

    /**
     * Auto-generate a prefixed teacher_id on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Teacher $model) {
            if (empty($model->teacher_id)) {
                $latest = static::orderByDesc('teacher_id')->value('teacher_id');
                $nextID = $latest ? intval(substr($latest, 3)) + 1 : 1;
                $model->teacher_id = '000' . sprintf('%03d', $nextID);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
