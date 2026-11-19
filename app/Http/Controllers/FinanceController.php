<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Finance & Cashflow Overview Dashboard
     */
    public function index(Request $request)
    {
        $accounts = BankAccount::orderByDesc('is_default')->orderBy('bank_name')->get();

        $totalLiquidFunds = (float) BankAccount::where('status', 'active')->sum('current_balance');
        $cashInHandBalance = (float) BankAccount::where('status', 'active')->where('account_type', 'petty_cash')->sum('current_balance');
        $bankBalances = (float) BankAccount::where('status', 'active')->where('account_type', '!=', 'petty_cash')->sum('current_balance');

        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthExpenses = (float) Expense::where('status', 'paid')->where('expense_date', '>=', $thisMonthStart)->sum('amount');
        $totalExpensesAllTime = (float) Expense::where('status', 'paid')->sum('amount');

        // Category-wise Breakdown (Current Month)
        $categoryBreakdown = ExpenseCategory::withSum(['expenses' => function ($q) use ($thisMonthStart) {
            $q->where('status', 'paid')->where('expense_date', '>=', $thisMonthStart);
        }], 'amount')->get()->filter(function ($cat) {
            return ($cat->expenses_sum_amount ?? 0) > 0;
        })->sortByDesc('expenses_sum_amount');

        // Recent 10 Transactions
        $recentTransactions = BankTransaction::with(['bankAccount', 'expense.category', 'user'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(10)
            ->get();

        // Recent 6 Expenses
        $recentExpenses = Expense::with(['category', 'bankAccount', 'user'])
            ->latest('expense_date')
            ->latest('id')
            ->take(6)
            ->get();

        $categories = ExpenseCategory::where('status', 'active')->get();

        return view('finance.index', compact(
            'accounts',
            'totalLiquidFunds',
            'cashInHandBalance',
            'bankBalances',
            'thisMonthExpenses',
            'totalExpensesAllTime',
            'categoryBreakdown',
            'recentTransactions',
            'recentExpenses',
            'categories'
        ));
    }

    /**
     * Cashbook & Bank Ledger
     */
    public function cashbook(Request $request)
    {
        $query = BankTransaction::with(['bankAccount', 'expense.category', 'transferToAccount', 'user'])
            ->latest('transaction_date')
            ->latest('id');

        if ($accountId = $request->query('account_id')) {
            $query->where('bank_account_id', $accountId);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('transaction_date', '>=', Carbon::parse($dateFrom));
        }

        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('transaction_date', '<=', Carbon::parse($dateTo));
        }

        $transactions = $query->paginate(25)->withQueryString();

        $accounts = BankAccount::orderBy('bank_name')->get();

        $totalCredit = (float) (clone $query)->where('type', 'credit')->sum('amount');
        $totalDebit = (float) (clone $query)->where('type', 'debit')->sum('amount');

        return view('finance.cashbook', compact(
            'transactions',
            'accounts',
            'totalCredit',
            'totalDebit'
        ));
    }

    /**
     * Fund Transfer between Accounts (e.g. Bank to Cash, Bank to Bank)
     */
    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_account_id' => ['required', 'exists:bank_accounts,id'],
            'to_account_id' => ['required', 'exists:bank_accounts,id', 'different:from_account_id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transfer_date' => ['required', 'date'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $amount = (float) $validated['amount'];

        DB::transaction(function () use ($validated, $amount) {
            $fromAccount = BankAccount::lockForUpdate()->findOrFail($validated['from_account_id']);
            $toAccount = BankAccount::lockForUpdate()->findOrFail($validated['to_account_id']);

            if ($fromAccount->current_balance < $amount) {
                throw new \Exception("Insufficient balance in {$fromAccount->bank_name} ({$fromAccount->account_number}). Current balance is PKR " . number_format($fromAccount->current_balance, 2));
            }

            // Deduct from source account
            $fromAccount->decrement('current_balance', $amount);
            $fromTxnNo = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            BankTransaction::create([
                'transaction_no' => $fromTxnNo,
                'bank_account_id' => $fromAccount->id,
                'type' => 'debit',
                'category' => 'transfer_out',
                'amount' => $amount,
                'balance_after' => $fromAccount->current_balance,
                'transaction_date' => $validated['transfer_date'],
                'reference_no' => $validated['reference_no'],
                'transfer_to_account_id' => $toAccount->id,
                'description' => "Fund Transfer to [{$toAccount->bank_name} - {$toAccount->account_title}]. " . ($validated['description'] ?? ''),
                'user_id' => Auth::id(),
            ]);

            // Add to destination account
            $toAccount->increment('current_balance', $amount);
            $toTxnNo = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            BankTransaction::create([
                'transaction_no' => $toTxnNo,
                'bank_account_id' => $toAccount->id,
                'type' => 'credit',
                'category' => 'transfer_in',
                'amount' => $amount,
                'balance_after' => $toAccount->current_balance,
                'transaction_date' => $validated['transfer_date'],
                'reference_no' => $validated['reference_no'],
                'transfer_to_account_id' => $fromAccount->id,
                'description' => "Fund Transfer received from [{$fromAccount->bank_name} - {$fromAccount->account_title}]. " . ($validated['description'] ?? ''),
                'user_id' => Auth::id(),
            ]);

            ActivityLog::record(
                action: 'created',
                module: 'Finance',
                description: "Transferred PKR " . number_format($amount, 2) . " from [{$fromAccount->bank_name}] to [{$toAccount->bank_name}].",
                properties: [
                    'from_account' => $fromAccount->bank_name,
                    'to_account' => $toAccount->bank_name,
                    'amount' => $amount,
                    'date' => $validated['transfer_date'],
                ]
            );
        });

        return redirect()->back()->with('success', 'Fund transfer of PKR ' . number_format($amount, 2) . ' executed successfully.');
    }
}
