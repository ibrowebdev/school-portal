<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_score',
        'max_score',
        'grade',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'min_score' => 'integer',
            'max_score' => 'integer',
        ];
    }

    /**
     * Look up the grade for a given score.
     */
    public static function getGrade(float $score): ?array
    {
        $setting = static::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();

        if (! $setting) {
            return null;
        }

        return [
            'grade' => $setting->grade,
            'remark' => $setting->remark,
        ];
    }
}
