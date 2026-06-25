<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvoiceCustomerName extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'customer_name',
        'po_number',
        'date',
        'due_date',
        'enable_tax',
        'recurring_incoice',
        'by_month',
        'month',
        'invoice_from',
        'invoice_to',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * Auto-generate a prefixed invoice_id on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (InvoiceCustomerName $model) {
            if (empty($model->invoice_id)) {
                $latest = static::orderByDesc('invoice_id')->value('invoice_id');
                $nextID = $latest ? intval(substr($latest, 5)) + 1 : 1;
                $model->invoice_id = 'IN0' . sprintf('%05d', $nextID);
            }
        });
    }

    // ─── Relationships ──────────────────────────────────────

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetails::class, 'invoice_id', 'invoice_id');
    }

    public function totalAmount(): HasOne
    {
        return $this->hasOne(InvoiceTotalAmount::class, 'invoice_id', 'invoice_id');
    }

    public function paymentDetails(): HasOne
    {
        return $this->hasOne(InvoicePaymentDetails::class, 'invoice_id', 'invoice_id');
    }

    public function additionalCharges(): HasMany
    {
        return $this->hasMany(InvoiceAdditionalCharges::class, 'invoice_id', 'invoice_id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(InvoiceDiscount::class, 'invoice_id', 'invoice_id');
    }
}
