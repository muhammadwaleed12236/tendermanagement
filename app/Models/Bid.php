<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'user_id',
        'bid_amount',
        'proposal_description',
        'document_path',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'bid_amount' => 'decimal:2',
            'submitted_at' => 'datetime',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function averageScore(): float
    {
        $evaluations = $this->evaluations;
        if ($evaluations->isEmpty()) {
            return 0;
        }

        $total = $evaluations->sum(function ($eval) {
            return ($eval->technical_score + $eval->financial_score) / 2;
        });

        return round($total / $evaluations->count(), 2);
    }
}
