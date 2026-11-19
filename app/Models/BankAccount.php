<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'branch_name',
        'branch_code',
        'account_type',
        'currency',
        'opening_balance',
        'current_balance',
        'status',
        'is_default',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class, 'bank_account_id')->latest('transaction_date');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'bank_account_id');
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'PKR ' . number_format($this->current_balance, 2);
    }

    public function getAccountTypeBadge(): array
    {
        return match ($this->account_type) {
            'petty_cash' => ['label' => 'Cash in Hand', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'savings' => ['label' => 'Savings Account', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
            'escrow' => ['label' => 'Escrow / Security', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
            default => ['label' => 'Current Business', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
        };
    }
}
