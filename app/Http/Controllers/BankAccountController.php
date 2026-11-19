<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    /**
     * Display a listing of bank accounts and cash vaults
     */
    public function index()
    {
        $accounts = BankAccount::withCount('transactions')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalBalance = (float) BankAccount::where('status', 'active')->sum('current_balance');
        $cashBalance = (float) BankAccount::where('status', 'active')->where('account_type', 'petty_cash')->sum('current_balance');
        $bankBalance = (float) BankAccount::where('status', 'active')->where('account_type', '!=', 'petty_cash')->sum('current_balance');

        return view('finance.banks.index', compact('accounts', 'totalBalance', 'cashBalance', 'bankBalance'));
    }

    /**
     * Store a newly created bank account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:150'],
            'account_title' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:100'],
            'iban' => ['nullable', 'string', 'max:50'],
            'branch_name' => ['nullable', 'string', 'max:150'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'account_type' => ['required', 'in:current,savings,escrow,petty_cash'],
            'opening_balance' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $openingBalance = (float) $validated['opening_balance'];

        $account = DB::transaction(function () use ($validated, $openingBalance) {
            if (!empty($validated['is_default'])) {
                BankAccount::where('is_default', true)->update(['is_default' => false]);
            }

            $account = BankAccount::create([
                'bank_name' => $validated['bank_name'],
                'account_title' => $validated['account_title'],
                'account_number' => $validated['account_number'],
                'iban' => $validated['iban'] ?? null,
                'branch_name' => $validated['branch_name'] ?? null,
                'branch_code' => $validated['branch_code'] ?? null,
                'account_type' => $validated['account_type'],
                'currency' => 'PKR',
                'opening_balance' => $openingBalance,
                'current_balance' => $openingBalance,
                'status' => 'active',
                'is_default' => !empty($validated['is_default']),
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // If opening balance > 0, record opening transaction
            if ($openingBalance > 0) {
                BankTransaction::create([
                    'transaction_no' => 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                    'bank_account_id' => $account->id,
                    'type' => 'credit',
                    'category' => 'opening_balance',
                    'amount' => $openingBalance,
                    'balance_after' => $openingBalance,
                    'transaction_date' => now(),
                    'reference_no' => 'OPENING',
                    'description' => "Initial opening balance on account setup.",
                    'user_id' => Auth::id(),
                ]);
            }

            return $account;
        });

        ActivityLog::record(
            action: 'created',
            module: 'Finance',
            subjectId: $account->id,
            subjectType: BankAccount::class,
            description: "Created bank account [{$account->bank_name}] ({$account->account_number}) with initial balance of PKR " . number_format($openingBalance, 2),
            properties: $account->toArray()
        );

        return redirect()->route('banks.index')->with('success', "Bank account [{$account->bank_name}] added successfully.");
    }

    /**
     * Account Statement / Ledger
     */
    public function statement(BankAccount $bank, Request $request)
    {
        $query = $bank->transactions()->with(['expense.category', 'transferToAccount', 'user']);

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        $transactions = $query->paginate(30)->withQueryString();

        $totalCredit = (float) (clone $query)->where('type', 'credit')->sum('amount');
        $totalDebit = (float) (clone $query)->where('type', 'debit')->sum('amount');

        return view('finance.banks.statement', compact('bank', 'transactions', 'totalCredit', 'totalDebit'));
    }

    /**
     * Update bank account details
     */
    public function update(Request $request, BankAccount $bank)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:150'],
            'account_title' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:100'],
            'iban' => ['nullable', 'string', 'max:50'],
            'branch_name' => ['nullable', 'string', 'max:150'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'account_type' => ['required', 'in:current,savings,escrow,petty_cash'],
            'status' => ['required', 'in:active,inactive,closed'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            BankAccount::where('id', '!=', $bank->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $old = $bank->toArray();
        $bank->update([
            'bank_name' => $validated['bank_name'],
            'account_title' => $validated['account_title'],
            'account_number' => $validated['account_number'],
            'iban' => $validated['iban'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'branch_code' => $validated['branch_code'] ?? null,
            'account_type' => $validated['account_type'],
            'status' => $validated['status'],
            'is_default' => !empty($validated['is_default']),
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::record(
            action: 'updated',
            module: 'Finance',
            subjectId: $bank->id,
            subjectType: BankAccount::class,
            description: "Updated bank account details for [{$bank->bank_name}] ({$bank->account_number}).",
            properties: ['old' => $old, 'new' => $bank->fresh()->toArray()]
        );

        return redirect()->route('banks.index')->with('success', "Bank account [{$bank->bank_name}] updated successfully.");
    }

    /**
     * Delete bank account
     */
    public function destroy(BankAccount $bank)
    {
        if ($bank->transactions()->count() > 1) {
            return back()->with('error', "Cannot delete bank account [{$bank->bank_name}] because it has existing transactions. You can mark its status as 'inactive' instead.");
        }

        $bankName = $bank->bank_name;
        $accNo = $bank->account_number;
        $bank->delete();

        ActivityLog::record(
            action: 'deleted',
            module: 'Finance',
            description: "Deleted bank account [{$bankName}] ({$accNo}).",
            properties: ['bank_name' => $bankName, 'account_number' => $accNo]
        );

        return redirect()->route('banks.index')->with('success', "Bank account [{$bankName}] deleted successfully.");
    }
}
