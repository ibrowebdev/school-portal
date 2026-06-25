<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'subject_name',
        'class',
    ];

    /**
     * Auto-generate a prefixed subject_id on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Subject $model) {
            if (empty($model->subject_id)) {
                $latest = static::orderByDesc('subject_id')->value('subject_id');
                $nextID = $latest ? intval(substr($latest, 3)) + 1 : 1;
                $model->subject_id = 'PRE' . sprintf('%03d', $nextID);
            }
        });
    }
}
