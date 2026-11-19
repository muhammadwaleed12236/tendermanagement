<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'bill_id',
        'cost_center',
        'department_name',
        'company_name',
        'token_number',
        'document_number',
        'cheque_date',
        'cheque_amount',
        'bill_amount',
        'gst_amount',
        'itax_amount',
        'itax_5_5_amount',
        'itax_15_amount',
        'sst_amount',
        'sst_difference',
        'vendor_gst_difference',
        'agpr_purchased_gst',
        'agpr_purchased_itax',
        'agpr_repaired_sst',
        'agpr_repaired_itax',
        'xyz_commission',
        'our_commission',
        'other_commission',
        'commission_breakdown',
        'total_deduction',
        'amount_to_paid_department',
        'vendor_profit',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'cheque_date' => 'date',
            'cheque_amount' => 'decimal:2',
            'bill_amount' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'itax_amount' => 'decimal:2',
            'itax_5_5_amount' => 'decimal:2',
            'itax_15_amount' => 'decimal:2',
            'sst_amount' => 'decimal:2',
            'sst_difference' => 'decimal:2',
            'vendor_gst_difference' => 'decimal:2',
            'agpr_purchased_gst' => 'decimal:2',
            'agpr_purchased_itax' => 'decimal:2',
            'agpr_repaired_sst' => 'decimal:2',
            'agpr_repaired_itax' => 'decimal:2',
            'xyz_commission' => 'decimal:2',
            'our_commission' => 'decimal:2',
            'other_commission' => 'decimal:2',
            'commission_breakdown' => 'array',
            'total_deduction' => 'decimal:2',
            'amount_to_paid_department' => 'decimal:2',
            'vendor_profit' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
