<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderFinancialHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'tender_reference',
        'vendor_name',
        'amount',
        'action_date',
        'transaction_type',
        'current_status',
        'document_path',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'action_date' => 'datetime',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
