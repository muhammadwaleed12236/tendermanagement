<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'budget_head_id',
        'financial_year',
        'quarter',
        'amount',
        'head_allocations',
        'release_date',
        'reference_no',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'release_date' => 'date',
            'head_allocations' => 'array',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function budgetHead(): BelongsTo
    {
        return $this->belongsTo(DepartmentBudgetHead::class, 'budget_head_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
