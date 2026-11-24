<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

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
                if (strtolower($model->status) === 'accepted') {
                    $model->tender_outcome = 'won';
                } elseif (strtolower($model->status) === 'rejected') {
                    $model->tender_outcome = 'lost';
                }
            }
        });
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
     * Check if status is Accepted
     */
    public function isAccepted(): bool
    {
        return strtolower(trim($this->status ?? '')) === 'accepted';
    }

    /**
     * Check if status is Rejected
     */
    public function isRejected(): bool
    {
        return strtolower(trim($this->status ?? '')) === 'rejected';
    }
}
