<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'opening_balance',
        'current_balance',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getFormattedOpeningBalanceAttribute(): string
    {
        return 'PKR ' . number_format((float) $this->opening_balance, 2);
    }

    public function getFormattedCurrentBalanceAttribute(): string
    {
        return 'PKR ' . number_format((float) ($this->current_balance ?? $this->opening_balance), 2);
    }

    /**
     * Recalculate vendor's current balance:
     * Current Balance = Opening Balance + Total Unpaid Purchases
     */
    public function recalculateBalance(): void
    {
        $unpaidPurchasesTotal = (float) $this->purchases()->where('payment_status', 'unpaid')->sum('total_amount');
        $this->current_balance = (float) $this->opening_balance + $unpaidPurchasesTotal;
        $this->save();
    }
}
