<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'quarter',
        'tender_id',
        'vendor_id',
        'company_id',
        'department_id',
        'budget_head_id',
        'head_of_account',
        'head_code',
        'head_description',
        'cost_center',
        'gross_amount',
        'gst_rate',
        'gst_amount',
        'tax_deduction',
        'net_amount',
        'agpr_vendor_no',
        'vendor_type',
        'vendor_number',
        'strn_number',
        'ntn_number',
        'sst_number',
        'invoice_date',
        'due_date',
        'dispatch_date',
        'remarks',
        'milestone_description',
        'attachment_path',
        'status',
        'payment_reference',
        'paid_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'gst_rate' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'tax_deduction' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'invoice_date' => 'date',
            'due_date' => 'date',
            'dispatch_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Determine Financial Quarter (Jul-Sep = 1st Qtr, Oct-Dec = 2nd Qtr, Jan-Mar = 3rd Qtr, Apr-Jun = 4th Qtr)
     */
    public static function determineQuarter($date): string
    {
        $d = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        $month = (int) $d->format('n');
        return match (true) {
            $month >= 7 && $month <= 9 => '1st Qtr',
            $month >= 10 && $month <= 12 => '2nd Qtr',
            $month >= 1 && $month <= 3 => '3rd Qtr',
            default => '4th Qtr',
        };
    }

    public function getQuarterDisplayAttribute(): string
    {
        return $this->quarter ?: ($this->invoice_date ? self::determineQuarter($this->invoice_date) : '1st Qtr');
    }

    public function getHeadCodeDisplayAttribute(): string
    {
        if (!empty($this->head_code)) {
            if (preg_match('/(A\d{4,5})/i', trim($this->head_code), $m)) {
                return strtoupper($m[1]);
            }
            return trim($this->head_code);
        }
        if (!empty($this->head_of_account) && preg_match('/(A\d{4,5})/i', trim($this->head_of_account), $m)) {
            return strtoupper($m[1]);
        }
        return 'A03970';
    }

    public function getHeadDescriptionDisplayAttribute(): string
    {
        if (!empty($this->head_description)) {
            $desc = trim($this->head_description);
            if (!preg_match('/^(mr|ms|mrs|dr)\.?\s+/i', $desc) && !str_contains(strtolower($desc), 'ali raza')) {
                return $desc;
            }
        }
        if (!empty($this->head_of_account)) {
            $clean = preg_replace('/^(A\d{4,5})\s*[-:]*\s*/i', '', trim($this->head_of_account));
            if (!empty($clean) && !preg_match('/^(mr|ms|mrs|dr)\.?\s+/i', $clean) && !str_contains(strtolower($clean), 'ali raza')) {
                return $clean;
            }
        }
        return $this->milestone_description ?: 'Others';
    }

    public function getCostCenterCodeDisplayAttribute(): string
    {
        return $this->cost_center ?: ($this->department?->code ?: 'KA-8914');
    }

    public function getDepartmentNameDisplayAttribute(): string
    {
        return $this->department?->name ?: 'Chief Controller of Stamps Karachi';
    }

    public function getVendorNameDisplayAttribute(): string
    {
        return $this->company?->company_name 
            ?: ($this->vendor?->company_name ?: ($this->vendor?->name ?: 'Shaheen Enterprises'));
    }

    public function getGstDisplayAttribute(): string
    {
        if ((float)$this->gst_rate > 0) {
            return rtrim(rtrim(number_format((float)$this->gst_rate, 2), '0'), '.') . '%';
        }
        return '18%';
    }

    public function getFormattedCreateDateAttribute(): string
    {
        if ($this->invoice_date) {
            return $this->invoice_date->format('d-M-y');
        }
        return $this->created_at ? $this->created_at->format('d-M-y') : '';
    }

    public function getFormattedDispatchDateAttribute(): string
    {
        return $this->dispatch_date ? $this->dispatch_date->format('d-M-y') : '';
    }
}
