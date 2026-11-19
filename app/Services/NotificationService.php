<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Cheque;
use App\Models\Department;
use App\Models\BudgetRelease;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Dispatch notification to a list of users or a single user.
     * If uniqueKey is provided, checks if a notification with that key was already delivered in the past 24 hours.
     */
    public static function notify(
        User|Collection|array $recipients,
        string $title,
        string $message,
        string $type = 'general',
        string $level = 'info',
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?string $icon = null,
        ?string $uniqueKey = null
    ): void {
        try {
            $users = is_iterable($recipients) ? $recipients : collect([$recipients]);
            
            $notification = new SystemAlertNotification(
                title: $title,
                message: $message,
                type: $type,
                level: $level,
                actionUrl: $actionUrl,
                actionText: $actionText,
                icon: $icon,
                uniqueKey: $uniqueKey
            );

            foreach ($users as $user) {
                if (!$user instanceof User || !$user->exists) {
                    continue;
                }

                // If unique key exists, prevent duplicate for this user in last 24h
                if ($uniqueKey) {
                    $recentExists = $user->notifications()
                        ->where('created_at', '>=', now()->subHours(24))
                        ->where('data', 'like', '%"unique_key":"' . $uniqueKey . '"%')
                        ->exists();

                    if ($recentExists) {
                        continue;
                    }
                }

                $user->notify($notification);
            }
        } catch (\Throwable $e) {
            Log::warning("Notification dispatch failed: " . $e->getMessage());
        }
    }

    /**
     * Notify administrators & managers
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        string $type = 'general',
        string $level = 'info',
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?string $icon = null,
        ?string $uniqueKey = null
    ): void {
        $admins = User::whereIn('role', ['admin', 'manager'])->where('status', 'active')->get();
        if ($admins->isEmpty()) {
            $admins = User::where('role', 'admin')->get();
        }
        if ($admins->isEmpty()) {
            $admins = User::limit(5)->get();
        }
        self::notify($admins, $title, $message, $type, $level, $actionUrl, $actionText, $icon, $uniqueKey);
    }

    /**
     * Notify all active users
     */
    public static function notifyAll(
        string $title,
        string $message,
        string $type = 'general',
        string $level = 'info',
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?string $icon = null,
        ?string $uniqueKey = null
    ): void {
        $users = User::where('status', 'active')->get();
        self::notify($users, $title, $message, $type, $level, $actionUrl, $actionText, $icon, $uniqueKey);
    }

    // ==========================================
    // 1. TENDER NOTIFICATIONS
    // ==========================================

    public static function tenderCreated(Tender $tender): void
    {
        $mode = $tender->isEpad() ? 'EPAD 2.0' : 'Paper Tender';
        $deadline = $tender->closing_date 
            ? Carbon::parse($tender->closing_date)->format('d M Y, h:i A') 
            : ($tender->submission_deadline ? Carbon::parse($tender->submission_deadline)->format('d M Y, h:i A') : 'N/A');
        
        $deptName = $tender->department ? $tender->department->name : 'Department';

        self::notifyAdmins(
            title: "New Tender Published: {$tender->title}",
            message: "Tender #{$tender->effective_tender_no} ({$mode}) added for {$deptName}. Deadline: {$deadline}.",
            type: 'tender_new',
            level: 'info',
            actionUrl: route('tenders.show', $tender),
            actionText: 'View Tender',
            icon: 'tender'
        );
    }

    public static function tenderStageChanged(Tender $tender, string $stage, string $status): void
    {
        $isQualified = ($status === 'qualified');
        $title = $isQualified 
            ? "Stage 1 Qualified: Tender #{$tender->effective_tender_no}" 
            : "Stage 1 Disqualified: Tender #{$tender->effective_tender_no}";
        
        $msg = $isQualified 
            ? "Technical bid has been Qualified! Stage 2 Financial bid opening can now be scheduled."
            : "Technical bid was Disqualified (Lose). Tender process concluded.";

        self::notifyAdmins(
            title: $title,
            message: $msg,
            type: 'tender_stage',
            level: $isQualified ? 'success' : 'danger',
            actionUrl: route('tenders.show', $tender),
            actionText: 'Review Stage',
            icon: 'stage'
        );
    }

    public static function tenderOutcomeChanged(Tender $tender, string $outcome): void
    {
        $isWon = ($outcome === 'won');
        $title = $isWon 
            ? "Tender WON: #{$tender->effective_tender_no}" 
            : "Tender Marked Lost: #{$tender->effective_tender_no}";
        
        $msg = $isWon 
            ? "Congratulations! '{$tender->title}' has been awarded/won. Please upload Letter of Intent and Award Letter / Work Order."
            : "Tender '{$tender->title}' has been marked as Lost.";

        self::notifyAdmins(
            title: $title,
            message: $msg,
            type: 'tender_outcome',
            level: $isWon ? 'success' : 'warning',
            actionUrl: route('tenders.show', $tender),
            actionText: $isWon ? 'Upload Letters' : 'View Tender',
            icon: $isWon ? 'award' : 'lost'
        );
    }

    public static function tenderLettersUpdated(Tender $tender, string $letterType): void
    {
        $label = ($letterType === 'letter_of_intent') ? 'Letter of Intent (LOI)' : 'Award Letter / Work Order';
        $statusMsg = $tender->isDocumentsCompleted() 
            ? "All post-award documentation is now complete (100%)!" 
            : "1 document uploaded. Remaining document still pending.";

        self::notifyAdmins(
            title: "Tender Document Uploaded: {$label}",
            message: "{$label} attached for Won Tender #{$tender->effective_tender_no}. {$statusMsg}",
            type: 'tender_docs',
            level: $tender->isDocumentsCompleted() ? 'success' : 'info',
            actionUrl: route('tenders.show', $tender),
            actionText: 'View Documents',
            icon: 'document'
        );
    }

    public static function bidSecurityUpdated(Tender $tender): void
    {
        $type = strtoupper($tender->bid_security_type ?: 'CDR');
        $status = ucfirst(str_replace('_', ' ', $tender->bid_security_status ?: 'updated'));

        self::notifyAdmins(
            title: "Bid Security / CDR Updated: #{$tender->effective_tender_no}",
            message: "{$type} security details updated. Current Status: {$status}. Amount: PKR " . number_format((float)($tender->bid_security_amount ?? 0), 2),
            type: 'bid_security',
            level: 'info',
            actionUrl: route('tenders.show', $tender),
            actionText: 'View Security Details',
            icon: 'security'
        );
    }

    // ==========================================
    // 2. BILL & INVOICE NOTIFICATIONS
    // ==========================================

    public static function billCreated(Bill $bill): void
    {
        $dept = $bill->department ? $bill->department->name : 'Department';
        $amount = number_format((float)($bill->net_amount ?? $bill->gross_amount ?? 0), 2);
        $billNo = $bill->bill_number ?: $bill->id;

        self::notifyAdmins(
            title: "New Bill Generated: #{$billNo}",
            message: "Bill for {$dept} created with net payable amount PKR {$amount}.",
            type: 'bill_new',
            level: 'info',
            actionUrl: route('bills.show', $bill),
            actionText: 'Review Bill',
            icon: 'bill'
        );
    }

    public static function billStatusChanged(Bill $bill, ?string $oldStatus, string $newStatus): void
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $newStatus));
        $level = match($newStatus) {
            'paid' => 'success',
            'approved', 'verified_by_dept' => 'info',
            'rejected', 'cancelled' => 'danger',
            default => 'warning'
        };

        $billNo = $bill->bill_number ?: $bill->id;
        $amount = number_format((float)($bill->net_amount ?? $bill->gross_amount ?? 0), 2);

        self::notifyAdmins(
            title: "Bill #{$billNo} Status: {$statusLabel}",
            message: "Bill status transitioned to '{$statusLabel}'. Net Amount: PKR {$amount}",
            type: 'bill_status',
            level: $level,
            actionUrl: route('bills.show', $bill),
            actionText: 'View Bill',
            icon: 'bill'
        );
    }

    // ==========================================
    // 3. CHEQUE NOTIFICATIONS
    // ==========================================

    public static function chequeCreated(Cheque $cheque): void
    {
        $amount = number_format((float)($cheque->cheque_amount ?? 0), 2);
        $chequeNo = $cheque->document_number ?: $cheque->token_number;
        $company = $cheque->company_name ?: 'Company';

        self::notifyAdmins(
            title: "New Cheque Recorded: #{$chequeNo}",
            message: "Cheque #{$chequeNo} (Token: {$cheque->token_number}) of PKR {$amount} for {$company} recorded.",
            type: 'cheque_new',
            level: 'info',
            actionUrl: route('cheques.index'),
            actionText: 'View Cheques',
            icon: 'cheque'
        );
    }

    // ==========================================
    // 4. BUDGET RELEASES NOTIFICATIONS
    // ==========================================

    public static function budgetReleased(Department $dept, BudgetRelease $release): void
    {
        $amount = number_format((float)($release->amount ?? 0), 2);

        self::notifyAdmins(
            title: "Budget Released: {$dept->name}",
            message: "Sanction #{$release->reference_no} ({$release->quarter}) released funds of PKR {$amount} to {$dept->name}.",
            type: 'budget_released',
            level: 'success',
            actionUrl: route('departments.show', $dept),
            actionText: 'View Department Budget',
            icon: 'budget'
        );
    }

    // ==========================================
    // 5. AUTOMATED DYNAMIC SCANNER (DEADLINES, MISSING LETTERS, EXPIRIES)
    // ==========================================

    /**
     * Automatically scans system state and generates timely alerts if any condition is met.
     * Prevents duplication per day using unique keys.
     */
    public static function syncAutomatedAlerts(?User $forUser = null): void
    {
        try {
            $today = Carbon::today()->format('Y-m-d');

            // 1. Upcoming Tender Closing Deadlines (within 48 hours or closing today)
            $upcomingTenders = Tender::where('tender_outcome', 'in_progress')
                ->where(function ($q) {
                    $q->whereBetween('closing_date', [now(), now()->addHours(48)])
                      ->orWhereBetween('submission_deadline', [now(), now()->addHours(48)]);
                })
                ->get();

            foreach ($upcomingTenders as $tender) {
                $closeDate = $tender->closing_date ?: $tender->submission_deadline;
                $diffHours = round(now()->diffInHours(Carbon::parse($closeDate), false));
                $hoursText = $diffHours <= 0 ? "Closing very soon / today!" : "Closing in {$diffHours} hours";
                
                $uniqueKey = "tender_deadline_{$tender->id}_{$today}";
                self::notifyAdmins(
                    title: "Tender Closing Soon: #{$tender->effective_tender_no}",
                    message: "'{$tender->title}' is {$hoursText}. Ensure all bids and documentation are submitted in time.",
                    type: 'tender_deadline',
                    level: 'warning',
                    actionUrl: route('tenders.show', $tender),
                    actionText: 'View Tender',
                    icon: 'clock',
                    uniqueKey: $uniqueKey
                );
            }

            // 2. Won Tenders Missing Post-Award Letters (LOI or Award Letter pending)
            $wonTendersMissingDocs = Tender::where('tender_outcome', 'won')
                ->where(function ($q) {
                    $q->where('has_letter_of_intent', false)
                      ->orWhere('has_award_letter', false);
                })
                ->get();

            foreach ($wonTendersMissingDocs as $tender) {
                $missing = [];
                if (!$tender->has_letter_of_intent) $missing[] = 'Letter of Intent (LOI)';
                if (!$tender->has_award_letter) $missing[] = 'Award Letter / Work Order';
                $missingStr = implode(' & ', $missing);

                $uniqueKey = "won_docs_missing_{$tender->id}_{$today}";
                self::notifyAdmins(
                    title: "Award Documents Pending: #{$tender->effective_tender_no}",
                    message: "Tender #{$tender->effective_tender_no} ('{$tender->title}') is WON, but {$missingStr} is still missing. Please upload documents to complete records.",
                    type: 'tender_docs_pending',
                    level: 'warning',
                    actionUrl: route('tenders.show', $tender),
                    actionText: 'Upload Documents',
                    icon: 'alert',
                    uniqueKey: $uniqueKey
                );
            }

            // 3. Bid Security / CDR Expirations (expiring within 14 days or expired)
            $expiringSecurities = Tender::whereNotNull('bid_security_expiry_date')
                ->where('bid_security_status', 'submitted')
                ->where('bid_security_expiry_date', '<=', now()->addDays(14))
                ->get();

            foreach ($expiringSecurities as $tender) {
                $expiry = Carbon::parse($tender->bid_security_expiry_date);
                $isExpired = $expiry->isPast();
                $diff = $isExpired 
                    ? "EXPIRED on " . $expiry->format('d M Y') 
                    : "Expiring on " . $expiry->format('d M Y') . " (" . $expiry->diffForHumans() . ")";
                
                $uniqueKey = "bid_sec_expiry_{$tender->id}_{$today}";
                self::notifyAdmins(
                    title: ($isExpired ? "CDR / Bid Security Expired" : "CDR / Bid Security Expiring Soon"),
                    message: "Tender #{$tender->effective_tender_no}: {$tender->bid_security_type} of PKR " . number_format((float)($tender->bid_security_amount ?? 0), 2) . " is {$diff}. Please renew or request release.",
                    type: 'bid_security_expiring',
                    level: $isExpired ? 'danger' : 'warning',
                    actionUrl: route('tenders.show', $tender),
                    actionText: 'View Security',
                    icon: 'security',
                    uniqueKey: $uniqueKey
                );
            }

            // 4. Overdue Unpaid Bills
            $overdueBills = Bill::whereNotIn('status', ['paid', 'cancelled', 'rejected'])
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->startOfDay())
                ->get();

            foreach ($overdueBills as $bill) {
                $uniqueKey = "overdue_bill_{$bill->id}_{$today}";
                $billNo = $bill->bill_number ?: $bill->id;
                self::notifyAdmins(
                    title: "Overdue Bill: #{$billNo}",
                    message: "Bill for PKR " . number_format((float)($bill->net_amount ?? $bill->gross_amount ?? 0), 2) . " was due on " . Carbon::parse($bill->due_date)->format('d M Y') . " and is currently unpaid.",
                    type: 'bill_overdue',
                    level: 'danger',
                    actionUrl: route('bills.show', $bill),
                    actionText: 'Review Overdue Bill',
                    icon: 'bill',
                    uniqueKey: $uniqueKey
                );
            }

        } catch (\Throwable $e) {
            Log::warning("NotificationService::syncAutomatedAlerts error: " . $e->getMessage());
        }
    }
}
