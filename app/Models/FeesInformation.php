<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeesInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'fees_type',
        'fees_amount',
        'paid_date',
    ];

    protected function casts(): array
    {
        return [
            'fees_amount' => 'decimal:2',
            'paid_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
