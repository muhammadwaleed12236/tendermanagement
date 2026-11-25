<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'reference_no',
        'tender_no',
        'tender_type',
        'closing_date',
        'upload_date',
        'bidding_procedure',
        'collection_start_date',
        'collection_start_notes',
        'collection_end_date',
        'collection_end_notes',
        'category',
        'department_id',
        'company_id',
        'description',
        'requirements',
        'budget',
        'tender_fee_amount',
        'is_tender_fee_paid',
        'tender_fee_bank_account_id',
        'tender_fee_payment_mode',
        'tender_fee_ref_no',
        'tender_fee_date',
        'tender_fee_receipt_file',
        'bid_security_amount',
        'bid_security_percentage',
        'bid_security_type',
        'bid_security_bank_account_id',
        'bid_security_instrument_no',
        'bid_security_date',
        'bid_security_expiry_date',
        'bid_security_status',
        'bid_security_released_at',
        'bid_security_file',
        'awarded_amount',
        'work_order_no',
        'work_order_date',
        'submission_deadline',
        'opening_date',
        'technical_opening_date',
        'financial_opening_date',
        'current_stage',
        'stage_1_status',
        'stage_1_notes',
        'stage_1_completed_at',
        'stage_2_status',
        'stage_2_notes',
        'stage_2_completed_at',
        'tender_outcome',
        'has_letter_of_intent',
        'letter_of_intent_ref',
        'letter_of_intent_date',
        'letter_of_intent_file',
        'has_award_letter',
        'award_letter_ref',
        'award_letter_date',
        'award_letter_file',
        'documents_status',
        'documents_completed_at',
        'document_path',
        'status',
        'interest_status',
        'contact_person',
        'is_fully_processed',
        'initial_entry_only',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'upload_date' => 'date',
            'collection_start_date' => 'date',
            'collection_end_date' => 'date',
            'closing_date' => 'datetime',
            'submission_deadline' => 'datetime',
            'opening_date' => 'datetime',
            'technical_opening_date' => 'datetime',
            'financial_opening_date' => 'datetime',
            'stage_1_completed_at' => 'datetime',
            'stage_2_completed_at' => 'datetime',
            'has_letter_of_intent' => 'boolean',
            'has_award_letter' => 'boolean',
            'is_fully_processed' => 'boolean',
            'initial_entry_only' => 'boolean',
            'letter_of_intent_date' => 'date',
            'award_letter_date' => 'date',
            'documents_completed_at' => 'datetime',
            'budget' => 'decimal:2',
            'tender_fee_amount' => 'decimal:2',
            'is_tender_fee_paid' => 'boolean',
            'tender_fee_date' => 'date',
            'bid_security_amount' => 'decimal:2',
            'bid_security_percentage' => 'decimal:2',
            'bid_security_date' => 'date',
            'bid_security_expiry_date' => 'date',
            'bid_security_released_at' => 'datetime',
            'awarded_amount' => 'decimal:2',
            'work_order_date' => 'date',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tenderFeeBankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'tender_fee_bank_account_id');
    }

    public function bidSecurityBankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bid_security_bank_account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function isExpired(): bool
    {
        return $this->submission_deadline < now();
    }

    public function isInterested(): bool
    {
        return ($this->interest_status ?? 'interested') === 'interested';
    }

    public function isNotInterested(): bool
    {
        return $this->interest_status === 'not_interested';
    }

    public function isOpen(): bool
    {
        return $this->status === 'published' && !$this->isExpired() && $this->tender_outcome === 'in_progress';
    }

    public function isEpad(): bool
    {
        return $this->tender_type === 'epad';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->isEpad() ? 'EPAD 2.0' : 'Tender';
    }

    public function isTwoEnvelope(): bool
    {
        return $this->bidding_procedure === 'single_stage_two_envelope';
    }

    public function isOneEnvelope(): bool
    {
        return $this->bidding_procedure === 'single_stage_one_envelope' || empty($this->bidding_procedure);
    }

    public function isStage1Cleared(): bool
    {
        return $this->stage_1_status === 'qualified';
    }

    public function isWon(): bool
    {
        return $this->tender_outcome === 'won' || $this->status === 'awarded' || $this->stage_2_status === 'won';
    }

    public function isLost(): bool
    {
        return $this->tender_outcome === 'lost' || $this->status === 'lost' || $this->stage_1_status === 'disqualified';
    }

    public function isDocumentsCompleted(): bool
    {
        return (bool) ($this->has_letter_of_intent && $this->has_award_letter);
    }

    public function completedDocumentsCount(): int
    {
        $count = 0;
        if ($this->has_letter_of_intent) $count++;
        if ($this->has_award_letter) $count++;
        return $count;
    }

    public function getProcedureLabelAttribute(): string
    {
        return $this->isTwoEnvelope() ? 'Single Stage Two Envelope' : 'Single Stage One Envelope';
    }

    public function getEffectiveTenderNoAttribute(): string
    {
        return (string) ($this->tender_no ?: ($this->reference_no ?: '-'));
    }

    public function getEffectiveWorkOrderNoAttribute(): ?string
    {
        return $this->work_order_no ?: $this->award_letter_ref;
    }
}
