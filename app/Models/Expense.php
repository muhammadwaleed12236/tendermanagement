<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'expense_category_id',
        'bank_account_id',
        'company_id',
        'department_id',
        'title',
        'amount',
        'expense_date',
        'payment_method',
        'cheque_no',
        'payee_name',
        'receipt_file',
        'description',
        'status',
        'user_id',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class, 'expense_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'PKR ' . number_format($this->amount, 2);
    }

    public function getStatusBadge(): array
    {
        return match ($this->status) {
            'paid' => ['label' => 'Paid', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
            'pending' => ['label' => 'Pending Approval', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
            'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-slate-50 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'],
        };
    }
}
