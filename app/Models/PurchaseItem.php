<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'item_description',
        'unit',
        'quantity',
        'unit_price',
        'gst_percent',
        'gst_amount',
        'sst_percent',
        'sst_amount',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'gst_percent' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'sst_percent' => 'decimal:2',
            'sst_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getBaseAmountAttribute(): float
    {
        return (float) ($this->quantity * $this->unit_price);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->total_amount, 2);
    }
}
