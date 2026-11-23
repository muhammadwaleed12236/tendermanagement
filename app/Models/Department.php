<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'cost_center_description',
        'city',
        'head_of_department',
        'budget_allocated',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'budget_allocated' => 'decimal:2',
        ];
    }

    public function releases(): HasMany
    {
        return $this->hasMany(BudgetRelease::class);
    }

    public function budgetHeads(): HasMany
    {
        return $this->hasMany(DepartmentBudgetHead::class);
    }

    public function tenders(): HasMany
    {
        return $this->hasMany(Tender::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Total budget released (Quarterly/Tranches) so far
     */
    public function totalReleasedBudget(): float
    {
        $releases = (float) $this->releases()->sum('amount');
        if ($releases > 0) {
            return $releases;
        }
        $headsReleased = (float) $this->budgetHeads()->sum('released_budget');
        if ($headsReleased > 0) {
            return $headsReleased;
        }
        return (float) ($this->budget_allocated ?? 0);
    }

    /**
     * Total spent across paid bills
     */
    public function totalSpent(): float
    {
        return (float) $this->bills()->where('status', 'paid')->sum('net_amount');
    }

    /**
     * Available balance inside the RELEASED budget limit
     */
    public function availableReleasedBudget(): float
    {
        $released = $this->totalReleasedBudget();
        $spent = $this->totalSpent();
        return (float) max(0, $released - $spent);
    }

    /**
     * Remaining unspent balance from the overall allocated budget
     */
    public function remainingBudget(): float
    {
        return (float) max(0, $this->budget_allocated - $this->totalSpent());
    }

    /**
     * Remaining unreleased from original total allocated budget
     */
    public function unreleasedBudget(): float
    {
        $allocated = (float) $this->budget_allocated;
        $released = $this->totalReleasedBudget();
        return (float) max(0, $allocated - $released);
    }
}
