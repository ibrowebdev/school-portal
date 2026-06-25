<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'department_name',
        'head_of_department',
        'department_start_date',
        'no_of_students',
    ];

    protected function casts(): array
    {
        return [
            'department_start_date' => 'date',
            'no_of_students' => 'integer',
        ];
    }

    /**
     * Auto-generate a prefixed department_id on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Department $model) {
            if (empty($model->department_id)) {
                $latest = static::orderByDesc('department_id')->value('department_id');
                $nextID = $latest ? intval(substr($latest, 5)) + 1 : 1;
                $model->department_id = 'PRE_' . sprintf('%05d', $nextID);
            }
        });
    }
}
