<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_number',
        'vendor_id',
        'purchase_date',
        'payment_status',
        'subtotal_amount',
        'gst_amount',
        'sst_amount',
        'total_amount',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'subtotal_amount' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'sst_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->total_amount, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->subtotal_amount, 2);
    }

    public function getFormattedGstAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->gst_amount, 2);
    }

    public function getFormattedSstAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->sst_amount, 2);
    }
}
