<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'category',
        'due_date',
        'is_completed',
        'completed_at',
        'tender_id',
        'bill_id',
        'company_id',
        'created_by',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_completed', false)
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today());
    }

    public function scopeDueToday($query)
    {
        return $query->where('is_completed', false)
            ->whereDate('due_date', Carbon::today());
    }

    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_completed && $this->due_date && $this->due_date->isPast() && !$this->due_date->isToday();
    }

    public function getIsDueTodayAttribute(): bool
    {
        return !$this->is_completed && $this->due_date && $this->due_date->isToday();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'tender' => 'Tender Bid',
            'quotation' => 'Quotation Follow-up',
            'bill_invoice' => 'Bill & Invoice',
            'cheque_agpr' => 'Cheque & AGPR',
            default => 'General Task',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'tender' => 'clipboard-document-list',
            'quotation' => 'document-duplicate',
            'bill_invoice' => 'receipt-percent',
            'cheque_agpr' => 'banknotes',
            default => 'check-circle',
        };
    }
}
