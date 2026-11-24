<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Company;
use App\Models\Department;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    /**
     * Display listing of expenses
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'bankAccount', 'company', 'department', 'user'])->latest('expense_date')->latest('id');

        if ($catId = $request->query('category_id')) {
            $query->where('expense_category_id', $catId);
        }

        if ($method = $request->query('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($accountId = $request->query('bank_account_id')) {
            $query->where('bank_account_id', $accountId);
        }

        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('expense_date', '>=', Carbon::parse($dateFrom));
        }

        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('expense_date', '<=', Carbon::parse($dateTo));
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('payee_name', 'like', "%{$search}%")
                  ->orWhere('cheque_no', 'like', "%{$search}%");
            });
        }

        $expenses = $query->paginate(20)->withQueryString();

        // High level KPI metrics
        $thisMonth = Carbon::now()->startOfMonth();
        $totalPaidMonth = (float) Expense::where('status', 'paid')->where('expense_date', '>=', $thisMonth)->sum('amount');
        $totalPaidAllTime = (float) Expense::where('status', 'paid')->sum('amount');
        $totalCountMonth = Expense::where('expense_date', '>=', $thisMonth)->count();
        $pendingApprovalCount = Expense::where('status', 'pending')->count();

        $categories = ExpenseCategory::where('status', 'active')->orderBy('name')->get();
        $bankAccounts = BankAccount::where('status', 'active')->orderBy('bank_name')->get();

        return view('finance.expenses.index', compact(
            'expenses',
            'categories',
            'bankAccounts',
            'totalPaidMonth',
            'totalPaidAllTime',
            'totalCountMonth',
            'pendingApprovalCount'
        ));
    }

    /**
     * Show form for creating a new expense
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 'active')->orderBy('name')->get();
        $bankAccounts = BankAccount::where('status', 'active')->orderByDesc('is_default')->get();
        $companies = Company::where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        })->orderBy('company_name')->get();
        $departments = Department::orderBy('name')->get();

        // Generate next suggested voucher number
        $year = date('Y');
        $count = Expense::whereYear('created_at', $year)->count() + 1;
        $suggestedVoucherNo = 'EXP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('finance.expenses.create', compact('categories', 'bankAccounts', 'companies', 'departments', 'suggestedVoucherNo'));
    }

    /**
     * Store a newly created expense in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_no' => ['required', 'string', 'max:50', 'unique:expenses,voucher_no'],
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,cheque,online'],
            'cheque_no' => ['nullable', 'string', 'max:100'],
            'payee_name' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:paid,pending,cancelled'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'receipt_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $filename);
            $receiptPath = 'uploads/receipts/' . $filename;
        }

        $amount = (float) $validated['amount'];
        $status = $validated['status'];
        $bankAccountId = $validated['bank_account_id'] ?? null;

        $expense = DB::transaction(function () use ($validated, $receiptPath, $amount, $status, $bankAccountId) {
            $expense = Expense::create([
                'voucher_no' => $validated['voucher_no'],
                'expense_category_id' => $validated['expense_category_id'],
                'bank_account_id' => $bankAccountId,
                'company_id' => $validated['company_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'title' => $validated['title'],
                'amount' => $amount,
                'expense_date' => $validated['expense_date'],
                'payment_method' => $validated['payment_method'],
                'cheque_no' => $validated['cheque_no'] ?? null,
                'payee_name' => $validated['payee_name'] ?? null,
                'receipt_file' => $receiptPath,
                'description' => $validated['description'] ?? null,
                'status' => $status,
                'user_id' => Auth::id(),
                'approved_by' => $status === 'paid' ? Auth::id() : null,
            ]);

            // If expense is paid and tied to a bank/cash account, deduct balance & create debit transaction
            if ($status === 'paid' && $bankAccountId) {
                $account = BankAccount::lockForUpdate()->find($bankAccountId);
                if ($account) {
                    $account->decrement('current_balance', $amount);

                    $txnNo = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
                    BankTransaction::create([
                        'transaction_no' => $txnNo,
                        'bank_account_id' => $account->id,
                        'type' => 'debit',
                        'category' => 'expense',
                        'amount' => $amount,
                        'balance_after' => $account->current_balance,
                        'transaction_date' => $validated['expense_date'],
                        'reference_no' => $expense->voucher_no,
                        'expense_id' => $expense->id,
                        'description' => "Expense: {$expense->title} (Payee: " . ($expense->payee_name ?: 'N/A') . ")",
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return $expense;
        });

        $catName = $expense->category?->name ?? 'General';
        ActivityLog::record(
            action: 'created',
            module: 'Finance',
            subjectId: $expense->id,
            subjectType: Expense::class,
            description: "Recorded expense voucher [{$expense->voucher_no}] '{$expense->title}' of PKR " . number_format($amount, 2) . " under [{$catName}].",
            properties: $expense->toArray()
        );

        return redirect()->route('expenses.index')->with('success', "Expense voucher [{$expense->voucher_no}] recorded successfully.");
    }

    /**
     * Show expense voucher details / print format
     */
    public function show(Expense $expense)
    {
        $expense->load(['category', 'bankAccount', 'company', 'department', 'user', 'approver', 'transactions']);
        return view('finance.expenses.show', compact('expense'));
    }

    /**
     * Delete expense and revert bank balance
     */
    public function destroy(Expense $expense)
    {
        $voucherNo = $expense->voucher_no;
        $amount = (float) $expense->amount;
        $title = $expense->title;
        $bankAccountId = $expense->bank_account_id;
        $status = $expense->status;

        DB::transaction(function () use ($expense, $status, $bankAccountId, $amount) {
            // Revert bank balance if was paid and deducted
            if ($status === 'paid' && $bankAccountId) {
                $account = BankAccount::lockForUpdate()->find($bankAccountId);
                if ($account) {
                    $account->increment('current_balance', $amount);
                }
                // Delete associated bank transactions
                BankTransaction::where('expense_id', $expense->id)->delete();
            }

            $expense->delete();
        });

        ActivityLog::record(
            action: 'deleted',
            module: 'Finance',
            description: "Deleted expense voucher [{$voucherNo}] '{$title}' of PKR " . number_format($amount, 2) . ".",
            properties: ['voucher_no' => $voucherNo, 'amount' => $amount, 'title' => $title]
        );

        return redirect()->route('expenses.index')->with('success', "Expense voucher [{$voucherNo}] deleted successfully.");
    }

    /**
     * Expense Categories management
     */
    public function categories()
    {
        $categories = ExpenseCategory::withCount('expenses')->withSum('expenses', 'amount')->orderBy('name')->get();
        return view('finance.expenses.categories', compact('categories'));
    }

    /**
     * Store new Expense Category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:expense_categories,name'],
            'code' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $cat = ExpenseCategory::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'color' => $validated['color'] ?: '#6366f1',
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        ActivityLog::record(
            action: 'created',
            module: 'Finance',
            description: "Created expense category [{$cat->name}].",
            properties: $cat->toArray()
        );

        return redirect()->back()->with('success', "Expense category [{$cat->name}] created successfully.");
    }

    /**
     * Delete Expense Category
     */
    public function destroyCategory(ExpenseCategory $category)
    {
        if ($category->expenses()->count() > 0) {
            return back()->with('error', "Cannot delete category [{$category->name}] because it is currently assigned to existing expenses.");
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record(
            action: 'deleted',
            module: 'Finance',
            description: "Deleted expense category [{$name}].",
            properties: ['name' => $name]
        );

        return redirect()->back()->with('success', "Category [{$name}] deleted successfully.");
    }
}
