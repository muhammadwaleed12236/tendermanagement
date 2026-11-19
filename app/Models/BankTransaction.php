<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_no',
        'bank_account_id',
        'type',
        'category',
        'amount',
        'balance_after',
        'transaction_date',
        'reference_no',
        'expense_id',
        'cheque_id',
        'transfer_to_account_id',
        'description',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function transferToAccount()
    {
        return $this->belongsTo(BankAccount::class, 'transfer_to_account_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function cheque()
    {
        return $this->belongsTo(Cheque::class, 'cheque_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTypeBadge(): array
    {
        return $this->type === 'credit'
            ? ['label' => 'Deposit / Inflow', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'sign' => '+']
            : ['label' => 'Payment / Outflow', 'class' => 'bg-rose-50 text-rose-700 border-rose-200', 'sign' => '-'];
    }
}
