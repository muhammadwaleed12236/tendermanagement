<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EpadController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Departments Management
    Route::resource('departments', DepartmentController::class);
    Route::get('/budget-releases', [\App\Http\Controllers\BudgetReleaseController::class, 'index'])->name('releases.index');
    Route::post('/budget-releases', [\App\Http\Controllers\BudgetReleaseController::class, 'store'])->name('releases.store');
    Route::post('/departments/{department}/releases', [\App\Http\Controllers\BudgetReleaseController::class, 'store'])->name('departments.releases.store');
    Route::delete('/releases/{release}', [\App\Http\Controllers\BudgetReleaseController::class, 'destroy'])->name('releases.destroy');

    // Head-wise Budget Allocations (Detail Object Desc)
    Route::get('/departments/{department}/budget-heads', [\App\Http\Controllers\DepartmentBudgetHeadController::class, 'index'])->name('departments.budget-heads.index');
    Route::post('/departments/{department}/budget-heads', [\App\Http\Controllers\DepartmentBudgetHeadController::class, 'store'])->name('departments.budget-heads.store');
    Route::put('/budget-heads/{budgetHead}', [\App\Http\Controllers\DepartmentBudgetHeadController::class, 'update'])->name('budget-heads.update');
    Route::get('/budget-heads/{budgetHead}', [\App\Http\Controllers\DepartmentBudgetHeadController::class, 'show'])->name('budget-heads.show');
    Route::delete('/budget-heads/{budgetHead}', [\App\Http\Controllers\DepartmentBudgetHeadController::class, 'destroy'])->name('budget-heads.destroy');

    // Admin Tender Management & Reporting (Create, Reports & Pipeline before {tender})
    Route::get('/tenders/reports', [TenderController::class, 'reports'])->name('tenders.reports');
    Route::get('/tenders/reports/export', [TenderController::class, 'exportReports'])->name('tenders.reports.export');
    Route::get('/tenders/pipeline', [TenderController::class, 'pipeline'])->name('tenders.pipeline');
    Route::get('/tenders/create', [TenderController::class, 'create'])->name('tenders.create');
    Route::post('/tenders', [TenderController::class, 'store'])->name('tenders.store');
    Route::get('/tenders/{tender}/bidding-prep', [TenderController::class, 'biddingPrep'])->name('tenders.bidding-prep');
    Route::post('/tenders/{tender}/bidding-prep', [TenderController::class, 'saveBiddingPrep'])->name('tenders.bidding-prep.store');
    Route::post('/tenders/{tender}/interest-status', [TenderController::class, 'updateInterestStatus'])->name('tenders.interest-status');
    Route::get('/tenders/{tender}/edit', [TenderController::class, 'edit'])->name('tenders.edit');
    Route::put('/tenders/{tender}', [TenderController::class, 'update'])->name('tenders.update');
    Route::post('/tenders/{tender}/stage-status', [TenderController::class, 'updateStageStatus'])->name('tenders.stage-status');
    Route::post('/tenders/{tender}/post-award-documents', [TenderController::class, 'updatePostAwardDocuments'])->name('tenders.post-award-documents');
    Route::post('/tenders/{tender}/financial-securities', [TenderController::class, 'updateFinancialSecurities'])->name('tenders.financial-securities');
    Route::post('/tenders/{tender}/release-bid-security', [TenderController::class, 'releaseBidSecurity'])->name('tenders.release-bid-security');
    Route::post('/tenders/{tender}/performance-security', [TenderController::class, 'updatePerformanceSecurity'])->name('tenders.performance-security');
    Route::post('/tenders/{tender}/descriptions', [TenderController::class, 'updateDescriptions'])->name('tenders.descriptions.update');
    Route::delete('/tenders/{tender}', [TenderController::class, 'destroy'])->name('tenders.destroy');

    // ePads 2.0 Module (Dedicated & Completely Separate)
    Route::get('/epads', [EpadController::class, 'index'])->name('epads.index');
    Route::get('/epads/create', [EpadController::class, 'create'])->name('epads.create');
    Route::post('/epads', [EpadController::class, 'store'])->name('epads.store');
    Route::get('/epads/reports', [EpadController::class, 'reports'])->name('epads.reports');
    Route::get('/epads/reports/export', [EpadController::class, 'exportReports'])->name('epads.reports.export');
    Route::get('/epads/{epad}/edit', [EpadController::class, 'edit'])->name('epads.edit');
    Route::put('/epads/{epad}', [EpadController::class, 'update'])->name('epads.update');
    Route::patch('/epads/{epad}/ref', [EpadController::class, 'updateRef'])->name('epads.update-ref');
    Route::patch('/epads/{epad}/status', [EpadController::class, 'updateStatus'])->name('epads.update-status');
    Route::delete('/epads/{epad}', [EpadController::class, 'destroy'])->name('epads.destroy');

    // Bids
    Route::post('/tenders/{tender}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::get('/my-bids', [BidController::class, 'myBids'])->name('bids.my');
    Route::patch('/bids/{bid}/status', [BidController::class, 'updateStatus'])->name('bids.status');

    // Evaluations
    Route::post('/bids/{bid}/evaluate', [EvaluationController::class, 'store'])->name('evaluations.store');

    // Billing & Invoice Generation System
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
    Route::get('/bills/daily-report', [BillController::class, 'dailyReport'])->name('bills.daily-report');
    Route::get('/bills/daily-report/export', [BillController::class, 'exportDailyReport'])->name('bills.daily-report.export');
    Route::post('/bills/{bill}/quick-update-dispatch', [BillController::class, 'quickUpdateDispatch'])->name('bills.quick-update-dispatch');
    Route::get('/bills/{bill}', [BillController::class, 'show'])->name('bills.show');
    Route::get('/bills/{bill}/edit', [BillController::class, 'edit'])->name('bills.edit');
    Route::put('/bills/{bill}', [BillController::class, 'update'])->name('bills.update');
    Route::delete('/bills/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
    Route::get('/bills/{bill}/print', [BillController::class, 'print'])->name('bills.print');
    Route::get('/bills/{bill}/sales-tax-invoice', [BillController::class, 'salesTaxInvoice'])->name('bills.sales-tax-invoice');
    Route::get('/bills/{bill}/quotation/{vendor_num?}', [BillController::class, 'quotation'])->name('bills.quotation');
    Route::get('/bills/{bill}/comparative-statement', [BillController::class, 'comparativeStatement'])->name('bills.comparative-statement');
    Route::get('/bills/{bill}/invoice-design/{template?}', [BillController::class, 'invoiceDesign'])->name('bills.invoice-design');
    Route::patch('/bills/{bill}/status', [BillController::class, 'updateStatus'])->name('bills.status');

    // Cheques & Deductions Management
    Route::get('/cheques', [\App\Http\Controllers\ChequeController::class, 'index'])->name('cheques.index');
    Route::get('/cheques/create', [\App\Http\Controllers\ChequeController::class, 'create'])->name('cheques.create');
    Route::post('/cheques', [\App\Http\Controllers\ChequeController::class, 'store'])->name('cheques.store');
    Route::delete('/cheques/{cheque}', [\App\Http\Controllers\ChequeController::class, 'destroy'])->name('cheques.destroy');

    // Products Catalog & Bill Import System
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::post('/products/import', [\App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
    Route::get('/products/sample-csv', [\App\Http\Controllers\ProductController::class, 'sampleCsv'])->name('products.sample-csv');
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');

    // Companies & Supplier Setup & Letterhead Management
    Route::get('/companies/invoice-gallery', [\App\Http\Controllers\CompanyController::class, 'invoiceGallery'])->name('companies.invoice-gallery');
    Route::post('/companies/assign-template', [\App\Http\Controllers\CompanyController::class, 'assignTemplate'])->name('companies.assign-template');
    Route::get('/companies/letterheads', [\App\Http\Controllers\CompanyController::class, 'allLetterheads'])->name('companies.letterheads');
    Route::get('/companies/{company}/letterhead', [\App\Http\Controllers\CompanyController::class, 'letterhead'])->name('companies.letterhead');
    Route::post('/companies/{company}/letterhead', [\App\Http\Controllers\CompanyController::class, 'updateLetterhead'])->name('companies.letterhead.update');
    Route::post('/companies/{company}/letterhead/remove-image', [\App\Http\Controllers\CompanyController::class, 'removeLetterheadImage'])->name('companies.letterhead.remove-image');
    Route::resource('companies', \App\Http\Controllers\CompanyController::class);
    Route::patch('/companies/{company}/set-default', [\App\Http\Controllers\CompanyController::class, 'setDefault'])->name('companies.set-default');

    // User & Role Management
    Route::get('/roles', [\App\Http\Controllers\UserController::class, 'roles'])->name('roles.index');
    Route::post('/roles/permissions', [\App\Http\Controllers\UserController::class, 'updateRolePermissions'])->name('roles.update-permissions');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Audit Trail & Activity Logs ("Kis ne konsa kaam kiya hai")
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('/activity-logs/purge', [\App\Http\Controllers\ActivityLogController::class, 'purge'])->name('activity-logs.purge');

    // Reports & Analytics Hub & Master Data Management
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/master-data', [\App\Http\Controllers\ReportController::class, 'masterData'])->name('reports.master-data');
    Route::get('/reports/master-data/export', [\App\Http\Controllers\ReportController::class, 'exportMasterData'])->name('reports.master-data.export');

    // Finance, Cashbook & Bank Accounts
    Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/cashbook', [\App\Http\Controllers\FinanceController::class, 'cashbook'])->name('finance.cashbook');
    Route::post('/finance/transfer', [\App\Http\Controllers\FinanceController::class, 'transfer'])->name('finance.transfer');

    // Bank Accounts
    Route::get('/banks', [\App\Http\Controllers\BankAccountController::class, 'index'])->name('banks.index');
    Route::post('/banks', [\App\Http\Controllers\BankAccountController::class, 'store'])->name('banks.store');
    Route::get('/banks/{bank}/statement', [\App\Http\Controllers\BankAccountController::class, 'statement'])->name('banks.statement');
    Route::put('/banks/{bank}', [\App\Http\Controllers\BankAccountController::class, 'update'])->name('banks.update');
    Route::delete('/banks/{bank}', [\App\Http\Controllers\BankAccountController::class, 'destroy'])->name('banks.destroy');

    // Expenses & Vouchers
    Route::get('/expenses/categories', [\App\Http\Controllers\ExpenseController::class, 'categories'])->name('expenses.categories');
    Route::post('/expenses/categories', [\App\Http\Controllers\ExpenseController::class, 'storeCategory'])->name('expenses.categories.store');
    Route::delete('/expenses/categories/{category}', [\App\Http\Controllers\ExpenseController::class, 'destroyCategory'])->name('expenses.categories.destroy');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);

    // System Notifications Center
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/clear-read', [\App\Http\Controllers\NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/api/notifications/feed', [\App\Http\Controllers\NotificationController::class, 'apiFeed'])->name('notifications.api.feed');

    // To-Do List & Task Management
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggleComplete'])->name('todos.toggle');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public tender browsing
Route::get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
Route::get('/tenders/{tender}/download-document', [TenderController::class, 'downloadDocument'])->name('tenders.download-document');
Route::get('/tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');

require __DIR__.'/auth.php';
