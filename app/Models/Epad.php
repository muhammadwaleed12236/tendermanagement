<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Epad extends Tender
{
    protected $table = 'tenders';

    /**
     * Boot the model with global scope for tender_type = 'epad'
     */
    protected static function booted(): void
    {
        static::addGlobalScope('epad', function (Builder $builder) {
            $builder->where('tender_type', 'epad');
        });

        static::creating(function ($model) {
            $model->tender_type = 'epad';
            if (empty($model->bidding_procedure)) {
                $model->bidding_procedure = 'single_stage_one_envelope';
            }
            if (empty($model->submission_deadline) && !empty($model->closing_date)) {
                $model->submission_deadline = $model->closing_date;
            }
            if (empty($model->opening_date) && !empty($model->closing_date)) {
                $model->opening_date = $model->closing_date;
            }
            if (empty($model->status)) {
                $model->status = 'Accepted';
            }
        });

        static::updating(function ($model) {
            if (!empty($model->closing_date)) {
                $model->submission_deadline = $model->closing_date;
                $model->opening_date = $model->closing_date;
            }
            if (!empty($model->status)) {
                $st = strtolower($model->status);
                if (str_contains($st, 'accept') || str_contains($st, 'award')) {
                    $model->tender_outcome = 'won';
                } elseif (str_contains($st, 'reject') || str_contains($st, 'disqualif')) {
                    $model->tender_outcome = 'lost';
                } else {
                    $model->tender_outcome = 'in_progress';
                }
            }
        });
    }

    /**
     * Relationship to Vendor (Company)
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Accessor for Vendor Name
     */
    public function getVendorNameAttribute(): string
    {
        return $this->company ? ($this->company->company_name ?: $this->company->name) : 'Al Madina Enterprises';
    }

    /**
     * Accessor for Items / Work Description
     */
    public function getItemsDescriptionAttribute(): ?string
    {
        return $this->title;
    }

    /**
     * Mutator for Items / Work Description
     */
    public function setItemsDescriptionAttribute($value): void
    {
        $this->attributes['title'] = $value;
        $this->attributes['description'] = $value;
    }

    /**
     * Accessor for Procurement Type
     */
    public function getProcurementTypeAttribute(): ?string
    {
        return $this->category;
    }

    /**
     * Mutator for Procurement Type
     */
    public function setProcurementTypeAttribute($value): void
    {
        $this->attributes['category'] = $value;
    }

    /**
     * Accessor for Bid Amount
     */
    public function getBidAmountAttribute(): ?float
    {
        return (float) $this->budget;
    }

    /**
     * Mutator for Bid Amount
     */
    public function setBidAmountAttribute($value): void
    {
        $this->attributes['budget'] = $value;
    }

    /**
     * Accessor for Ref #
     */
    public function getRefNoAttribute(): ?string
    {
        return $this->reference_no;
    }

    /**
     * Mutator for Ref #
     */
    public function setRefNoAttribute($value): void
    {
        $this->attributes['reference_no'] = $value;
        if (!empty($value)) {
            $this->attributes['tender_no'] = $value;
        }
    }

    /**
     * Check if status is Accepted / Awarded
     */
    public function isAccepted(): bool
    {
        $st = strtolower(trim($this->status ?? ''));
        return str_contains($st, 'accept') || str_contains($st, 'award') || $this->tender_outcome === 'won';
    }

    /**
     * Check if status is Under Evaluation / Pending
     */
    public function isUnderEvaluation(): bool
    {
        $st = strtolower(trim($this->status ?? ''));
        return str_contains($st, 'evaluation') || str_contains($st, 'pending') || $st === 'draft' || $this->tender_outcome === 'in_progress';
    }

    /**
     * Check if status is Rejected / Disqualified
     */
    public function isRejected(): bool
    {
        $st = strtolower(trim($this->status ?? ''));
        return str_contains($st, 'reject') || str_contains($st, 'disqualif') || $this->tender_outcome === 'lost';
    }

    /**
     * Check if Letter of Intent (LOI) attachment exists
     */
    public function hasLoiAttachment(): bool
    {
        return !empty($this->letter_of_intent_file) && (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->letter_of_intent_file) || file_exists(public_path('storage/' . $this->letter_of_intent_file)));
    }
}
