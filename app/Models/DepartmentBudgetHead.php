<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentBudgetHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'year',
        'head_code',
        'head_name',
        'original_budget',
        'expenditure',
        'released_budget',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'original_budget' => 'decimal:2',
            'expenditure' => 'decimal:2',
            'released_budget' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Calculated released budget balance
     */
    public function getReleasedBalanceAttribute(): float
    {
        return max(0, (float) $this->released_budget - (float) $this->expenditure);
    }

    /**
     * Calculated remaining unreleased ceiling for this head
     */
    public function getUnreleasedCeilingAttribute(): float
    {
        return max(0, (float) $this->original_budget - (float) $this->released_budget);
    }
}
