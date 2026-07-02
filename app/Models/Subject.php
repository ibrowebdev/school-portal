<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    // ─── Relationships ─────────────────────────────────────────

    /**
     * Classes this subject is mapped to.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject')
            ->withTimestamps();
    }

    /**
     * Results recorded for this subject.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
