<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Department;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Bill::with(['tender', 'vendor', 'department', 'items']);

        // Vendors can only view their own bills
        if ($user && $user->isVendor()) {
            $query->where('vendor_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhere('milestone_description', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $bills = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::where('status', 'active')->get();
        $companies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();

        $stats = [
            'total_amount' => (clone $query)->sum('gross_amount'),
            'paid_amount' => (clone $query)->where('status', 'paid')->sum('net_amount'),
            'pending_count' => (clone $query)->whereIn('status', ['pending', 'verified_by_dept'])->count(),
            'approved_count' => (clone $query)->where('status', 'approved')->count(),
        ];

        return view('bills.index', compact('bills', 'departments', 'stats', 'companies'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->isVendor()) {
            $vendors = collect([$user]);
        } else {
            $vendors = User::where('role', 'vendor')->get();
        }

        // Load departments with released budget info and their budget heads
        $departments = Department::where('status', 'active')->with(['releases', 'bills', 'budgetHeads'])->get();
        $tenders = Tender::with('department')->get();
        $selectedTenderId = $request->get('tender_id');
        $selectedDeptId = $request->get('department_id');

        // Auto-suggest next sequential numeric bill number
        $latestNumeric = Bill::all()->filter(function ($b) {
            return is_numeric($b->bill_number);
        })->max(function ($b) {
            return (int) $b->bill_number;
        });

        $suggestedBillNumber = $latestNumeric ? ((string) ($latestNumeric + 1)) : '11710';

        // Load all catalog products for instant datalist autocomplete & line item selection
        $products = \App\Models\Product::orderBy('description')->get(['id', 'description', 'unit', 'default_price']);

        // Load configured companies / suppliers (Name, Vendor No, NTN, GST, SST)
        $companies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();
        $defaultCompany = $companies->firstWhere('is_default', true) ?: $companies->first();

        return view('bills.create', compact('departments', 'vendors', 'tenders', 'selectedTenderId', 'selectedDeptId', 'suggestedBillNumber', 'products', 'companies', 'defaultCompany'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'budget_head_id' => 'nullable|exists:department_budget_heads,id',
            'company_id' => 'nullable|exists:companies,id',
            'vendor_id' => 'nullable|exists:users,id',
            'tender_id' => 'nullable|exists:tenders,id',
            'bill_number' => 'nullable|string|max:50',
            'quarter' => 'nullable|string|max:50',
            'head_of_account' => 'nullable|string|max:255',
            'head_code' => 'nullable|string|max:50',
            'head_description' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:50',
            'gst_rate' => 'nullable|numeric|min:0',
            'gst_amount' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'dispatch_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:255',
            'milestone_description' => 'nullable|string',
            'tax_deduction' => 'nullable|numeric|min:0',
            'agpr_vendor_no' => 'nullable|string|max:50',
            'strn_number' => 'nullable|string|max:50',
            'ntn_number' => 'nullable|string|max:50',
            'sst_number' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|mimes:pdf,docx,zip,jpg,png|max:20480',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'department_id.required' => 'Target Department select karna lazmi hai.',
            'items.required' => 'Kam az kam ek product item add karna lazmi hai.',
            'items.*.item_name.required' => 'Har line item ka naam / description lazmi hai.',
        ]);

        $department = Department::findOrFail($validated['department_id']);
        $vendorId = $validated['vendor_id'] ?? null;
        if (!$vendorId && $user->isVendor()) {
            $vendorId = $user->id;
        }
        $vendor = $vendorId ? User::find($vendorId) : null;

        // Vendors can only submit bills for themselves
        if ($user->isVendor() && $vendorId && $vendorId != $user->id) {
            abort(403, 'Unauthorized vendor submission.');
        }

        // Calculate gross total from all line items
        $gross = 0;
        $itemsData = [];
        foreach ($validated['items'] as $item) {
            $qty = (float) $item['quantity'];
            $price = (float) $item['unit_price'];
            $total = $qty * $price;
            $gross += $total;

            $itemsData[] = [
                'item_name' => $item['item_name'],
                'unit' => $item['unit'] ?? 'Nos',
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $total,
            ];
        }

        if ($gross <= 0) {
            return back()->withInput()->with('error', 'Invoice must have at least one product with a valid price and quantity.');
        }

        // GST Calculation (Support both PKR direct amount or % rate)
        if ($request->filled('gst_amount') && (float) $request->gst_amount > 0) {
            $gstAmount = round((float) $request->gst_amount, 2);
            $gstRate = $gross > 0 ? round(($gstAmount / $gross) * 100, 2) : (float) ($validated['gst_rate'] ?? 18.00);
        } else {
            $gstRate = isset($validated['gst_rate']) ? (float) $validated['gst_rate'] : 18.00;
            $gstAmount = round(($gross * $gstRate) / 100, 2);
        }

        $taxDeduction = (float) ($validated['tax_deduction'] ?? 0);
        $totalPayable = max(0, $gross + $gstAmount - $taxDeduction);

        // Budget Limit Notice (Non-blocking warning so user bill is never lost)
        $availableReleasedBudget = $department->availableReleasedBudget();
        $warningNotice = '';
        if ($totalPayable > $availableReleasedBudget) {
            $warningNotice = ' (Notice: Bill amount PKR ' . number_format($totalPayable, 2) . ' exceeds currently released ceiling PKR ' . number_format($availableReleasedBudget, 2) . '; saved as Pending for tranche authorization).';
        }

        $docPath = null;
        if ($request->hasFile('attachment')) {
            $docPath = $request->file('attachment')->store('bills_invoices', 'public');
        }

        // Auto-assign unique bill number if provided is blank or taken
        $requestedBillNo = trim($validated['bill_number'] ?? '');
        if (empty($requestedBillNo) || Bill::where('bill_number', $requestedBillNo)->exists()) {
            $latestNumeric = Bill::all()->filter(fn($b) => is_numeric($b->bill_number))->max(fn($b) => (int)$b->bill_number);
            $billNumber = ($requestedBillNo && !Bill::where('bill_number', $requestedBillNo)->exists())
                ? $requestedBillNo
                : (string)(($latestNumeric ?: 11710) + 1);
        } else {
            $billNumber = $requestedBillNo;
        }

        $milestoneDesc = !empty($validated['milestone_description']) 
            ? trim($validated['milestone_description']) 
            : 'Procurement of goods / store items as per bill';

        $companyId = $validated['company_id'] ?? null;
        $company = $companyId ? \App\Models\Company::find($companyId) : null;
        if (!$company) {
            $company = \App\Models\Company::where('is_default', true)->first();
            if ($company) {
                $companyId = $company->id;
            }
        }

        $agprVendorNo = !empty($validated['agpr_vendor_no']) ? $validated['agpr_vendor_no'] : ($company?->vendor_no ?? ($vendor?->agpr_vendor_no ?? '30368984'));
        $strnNumber = !empty($validated['strn_number']) ? $validated['strn_number'] : ($company?->gst ?? ($vendor?->strn_number ?? '3277876289057'));
        $ntnNumber = !empty($validated['ntn_number']) ? $validated['ntn_number'] : ($company?->ntn ?? ($vendor?->tax_number ?? '3437101-0'));
        $sstNumber = !empty($validated['sst_number']) ? $validated['sst_number'] : ($company?->sst ?? 'S3437101-2');

        $headCode = !empty($validated['head_code']) ? trim($validated['head_code']) : null;
        $headDesc = !empty($validated['head_description']) ? trim($validated['head_description']) : null;
        $headOfAccount = $headCode 
            ? ($headCode . ($headDesc ? ' - ' . $headDesc : '')) 
            : (!empty($validated['head_of_account']) ? $validated['head_of_account'] : 'Cost of Other Stores');

        $billQuarter = !empty($validated['quarter']) 
            ? $validated['quarter'] 
            : Bill::determineQuarter($validated['invoice_date']);

        $costCenter = !empty($validated['cost_center']) 
            ? trim($validated['cost_center']) 
            : ($department->code ?: 'KA-8914');

        $bill = Bill::create([
            'bill_number' => $billNumber,
            'quarter' => $billQuarter,
            'department_id' => $department->id,
            'budget_head_id' => $validated['budget_head_id'] ?? null,
            'tender_id' => $validated['tender_id'] ?? null,
            'vendor_id' => $vendorId,
            'company_id' => $companyId,
            'head_of_account' => $headOfAccount,
            'head_code' => $headCode,
            'head_description' => $headDesc,
            'cost_center' => $costCenter,
            'gross_amount' => $gross,
            'gst_rate' => $gstRate,
            'gst_amount' => $gstAmount,
            'tax_deduction' => $taxDeduction,
            'net_amount' => $totalPayable,
            'agpr_vendor_no' => $agprVendorNo,
            'strn_number' => $strnNumber,
            'ntn_number' => $ntnNumber,
            'sst_number' => $sstNumber,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'dispatch_date' => $validated['dispatch_date'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'milestone_description' => $milestoneDesc,
            'attachment_path' => $docPath,
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        // Save multiple product line items
        foreach ($itemsData as $item) {
            $bill->items()->create($item);
        }

        // Record expenditure on selected budget head if provided
        if (!empty($validated['budget_head_id'])) {
            $budgetHead = \App\Models\DepartmentBudgetHead::find($validated['budget_head_id']);
            if ($budgetHead) {
                $budgetHead->increment('expenditure', $totalPayable);
            }
        }

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Bills',
            description: "Generated Bill #{$bill->bill_number} for " . ($bill->department?->name ?? 'Department') . " (PKR " . number_format($bill->net_amount) . ") with " . count($itemsData) . " product items.",
            subject: $bill,
            properties: [
                'bill_number' => $bill->bill_number,
                'net_amount' => $bill->net_amount,
                'department' => $bill->department?->name,
                'company' => $bill->company?->company_name ?? ($bill->vendor?->company_name ?? 'Direct'),
                'items_count' => count($itemsData),
            ]
        );

        \App\Services\NotificationService::billCreated($bill);

        return redirect()->route('bills.show', $bill)->with('success', 'Bill / Invoice ' . $billNumber . ' created successfully with ' . count($itemsData) . ' product items!' . $warningNotice);
    }

    public function show(Bill $bill)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);
        $companies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();

        return view('bills.show', compact('bill', 'companies'));
    }

    public function print(Bill $bill)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);

        return view('bills.print', compact('bill'));
    }

    /**
     * Sales Tax Invoice (Formatted exactly as per official sales tax invoice format)
     */
    public function salesTaxInvoice(Bill $bill)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);

        $gstRate = (float) ($bill->gst_rate > 0 ? $bill->gst_rate : 18.00);

        $items = [];
        $totalQty = 0;
        $totalExclTax = 0;
        $totalGst = 0;
        $totalInclTax = 0;

        foreach ($bill->items as $item) {
            $qty = (float) $item->quantity;
            $unitPrice = (float) $item->unit_price;
            $valExcl = round($qty * $unitPrice, 2);
            $gstAmount = round($valExcl * ($gstRate / 100), 2);
            $valIncl = round($valExcl + $gstAmount, 2);

            $items[] = [
                'name' => $item->item_name,
                'qty' => $qty,
                'unit' => $item->unit ?: 'Nos',
                'unit_price' => $unitPrice,
                'val_excl_tax' => $valExcl,
                'gst_rate' => $gstRate,
                'gst_amount' => $gstAmount,
                'val_incl_tax' => $valIncl,
            ];

            $totalQty += $qty;
            $totalExclTax += $valExcl;
            $totalGst += $gstAmount;
            $totalInclTax += $valIncl;
        }

        return view('bills.sales_tax_invoice', compact('bill', 'items', 'totalQty', 'totalExclTax', 'totalGst', 'totalInclTax', 'gstRate'));
    }

    /**
     * Quotation (1 = Original Base, 2 = +5%, 3 = +8%)
     */
    public function quotation(Request $request, Bill $bill, $vendor_num = 1)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);

        $allCompanies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();

        $vendorNum = (int) $vendor_num;
        if (!in_array($vendorNum, [1, 2, 3])) {
            $vendorNum = 1;
        }

        // Determine which company to use (From query param ?company_id= or sequential defaults)
        $chosenCompanyId = $request->query('company_id');
        $chosenCompany = null;
        if ($chosenCompanyId) {
            $chosenCompany = $allCompanies->firstWhere('id', $chosenCompanyId) ?: \App\Models\Company::find($chosenCompanyId);
        }

        if (!$chosenCompany) {
            if ($vendorNum === 1) {
                // Q1 defaults to Bill company or primary default
                $chosenCompany = $bill->company ?: ($allCompanies->firstWhere('is_default', true) ?: $allCompanies->first());
            } elseif ($vendorNum === 2) {
                // Q2 defaults to 2nd distinct company in database
                $chosenCompany = $allCompanies->where('id', '!=', $bill->company_id)->skip(0)->first() ?: $allCompanies->first();
            } else {
                // Q3 defaults to 3rd distinct company in database
                $chosenCompany = $allCompanies->where('id', '!=', $bill->company_id)->skip(1)->first() 
                    ?: $allCompanies->where('id', '!=', $bill->company_id)->first() 
                    ?: $allCompanies->first();
            }
        }

        // Markup percentage
        if ($vendorNum === 1) {
            $markupPercent = 0;
        } elseif ($vendorNum === 2) {
            $markupPercent = (float) $request->query('markup', 5); // +5%
        } else {
            $markupPercent = (float) $request->query('markup', 8); // +8%
        }

        // Supplier details from chosen company (with graceful fallbacks)
        $vendorName = $chosenCompany?->company_name ?: ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON');
        $vendorAddress = $chosenCompany?->address ?: 'Office # 1/2 Block # 6, PECHS Nursery Karachi';
        $vendorPhone = $chosenCompany?->contact_number ?: ($bill->vendor?->phone ?: '0331-2449892');
        $vendorNtn = $chosenCompany?->ntn ?: ($bill->ntn_number ?: ($bill->vendor?->tax_number ?: '3437101-0'));
        $vendorStrn = $chosenCompany?->gst ?: ($bill->strn_number ?: ($bill->vendor?->strn_number ?: '3277876289057'));
        $vendorSst = $chosenCompany?->sst ?: ($bill->sst_number ?: 'S3437101-2');

        $factor = 1 + ($markupPercent / 100);

        $items = [];
        $totalGross = 0;

        foreach ($bill->items as $item) {
            $qty = (float) $item->quantity;
            $basePrice = (float) $item->unit_price;
            $quotedPrice = round($basePrice * $factor, 2);
            $totalPrice = round($qty * $quotedPrice, 2);

            $items[] = [
                'name' => $item->item_name,
                'qty' => $qty,
                'unit' => $item->unit ?: 'Nos',
                'base_price' => $basePrice,
                'unit_price' => $quotedPrice,
                'total_price' => $totalPrice,
            ];

            $totalGross += $totalPrice;
        }

        $gstRate = (float) ($bill->gst_rate > 0 ? $bill->gst_rate : 18.00);
        $gstAmount = round($totalGross * ($gstRate / 100), 2);
        $grandTotal = $totalGross + $gstAmount;

        $showUnitColumn = $request->has('unit') ? $request->boolean('unit') : ($chosenCompany?->show_unit_column ?? true);
        $showSrColumn = $request->has('sr') ? $request->boolean('sr') : ($chosenCompany?->show_sr_column ?? true);
        $totalLayout = $request->query('total_layout') ?: ($chosenCompany?->total_layout ?: 'auto');
        $totalLayoutOptions = \App\Models\Company::totalLayoutOptions();

        return view('bills.quotation', compact(
            'bill', 'vendorNum', 'markupPercent', 'vendorName', 'vendorAddress', 
            'vendorPhone', 'vendorNtn', 'vendorStrn', 'vendorSst', 'items', 'totalGross', 
            'gstRate', 'gstAmount', 'grandTotal', 'allCompanies', 'chosenCompany',
            'showUnitColumn', 'showSrColumn', 'totalLayout', 'totalLayoutOptions'
        ));
    }

    /**
     * 3-Vendor Comparative Statement (CS) Sheet
     */
    public function comparativeStatement(Request $request, Bill $bill)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);

        $allCompanies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();

        // Resolve Company 1
        $c1Id = $request->query('c1', $bill->company_id);
        $company1 = $c1Id ? $allCompanies->firstWhere('id', $c1Id) : ($bill->company ?: $allCompanies->first());

        // Resolve Company 2
        $c2Id = $request->query('c2');
        if ($c2Id) {
            $company2 = $allCompanies->firstWhere('id', $c2Id);
        } else {
            $company2 = $allCompanies->where('id', '!=', $company1?->id)->skip(0)->first() ?: $allCompanies->first();
        }

        // Resolve Company 3
        $c3Id = $request->query('c3');
        if ($c3Id) {
            $company3 = $allCompanies->firstWhere('id', $c3Id);
        } else {
            $company3 = $allCompanies->where('id', '!=', $company1?->id)->where('id', '!=', $company2?->id)->first() 
                ?: $allCompanies->where('id', '!=', $company1?->id)->first() 
                ?: $allCompanies->first();
        }

        $vendor1Name = $company1?->company_name ?: ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON');
        $vendor2Name = $company2?->company_name ?: 'M/S. BuildWell Engineering & Construction';
        $vendor3Name = $company3?->company_name ?: 'M/S. Apex IT Solutions & Technologies';

        $items = [];
        $totalQ1 = 0;
        $totalQ2 = 0;
        $totalQ3 = 0;

        foreach ($bill->items as $item) {
            $qty = (float) $item->quantity;
            $rate1 = (float) $item->unit_price;
            $tot1 = round($qty * $rate1, 2);

            $rate2 = round($rate1 * 1.05, 2);
            $tot2 = round($qty * $rate2, 2);

            $rate3 = round($rate1 * 1.08, 2);
            $tot3 = round($qty * $rate3, 2);

            $items[] = [
                'name' => $item->item_name,
                'unit' => $item->unit ?: 'Nos',
                'qty' => $qty,
                'rate1' => $rate1,
                'tot1' => $tot1,
                'rate2' => $rate2,
                'tot2' => $tot2,
                'rate3' => $rate3,
                'tot3' => $tot3,
            ];

            $totalQ1 += $tot1;
            $totalQ2 += $tot2;
            $totalQ3 += $tot3;
        }

        $gstRate = (float) ($bill->gst_rate > 0 ? $bill->gst_rate : 18.00);

        $gst1 = round($totalQ1 * ($gstRate / 100), 2);
        $net1 = $totalQ1 + $gst1;

        $gst2 = round($totalQ2 * ($gstRate / 100), 2);
        $net2 = $totalQ2 + $gst2;

        $gst3 = round($totalQ3 * ($gstRate / 100), 2);
        $net3 = $totalQ3 + $gst3;

        return view('bills.comparative_statement', compact(
            'bill', 'vendor1Name', 'vendor2Name', 'vendor3Name', 'company1', 'company2', 'company3',
            'allCompanies', 'items', 'totalQ1', 'totalQ2', 'totalQ3', 'gstRate', 'gst1', 'gst2', 'gst3',
            'net1', 'net2', 'net3'
        ));
    }

    /**
     * Commercial Invoice in Multi-Pattern Styles (7 Designs)
     */
    public function invoiceDesign(Request $request, Bill $bill, $template = null)
    {
        $user = Auth::user();
        if ($user && $user->isVendor() && $bill->vendor_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $bill->load(['tender', 'vendor', 'company', 'department', 'creator', 'items']);

        $allCompanies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();
        $templateOptions = \App\Models\Company::templateOptions();

        // 1. Resolve Company
        $companyId = $request->query('company_id', $bill->company_id);
        $company = $companyId ? ($allCompanies->firstWhere('id', $companyId) ?: \App\Models\Company::find($companyId)) : ($bill->company ?: $allCompanies->first());

        // 2. Resolve Template: from route parameter -> query param -> company's invoice_template -> default
        $activeTemplate = $template ?: ($request->query('template') ?: ($company?->invoice_template ?: 'navy_wave'));
        if (!array_key_exists($activeTemplate, $templateOptions)) {
            $activeTemplate = 'navy_wave';
        }

        // 3. Optional Markup (e.g. for Quotation 2 at +5% or Quotation 3 at +8%)
        $markupPercent = (float) $request->query('markup', 0);
        $factor = 1 + ($markupPercent / 100);

        // Supplier Details
        $vendorName = $company?->company_name ?: ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON');
        $vendorAddress = $company?->address ?: 'Office # 1/2 Block # 6, PECHS Nursery Karachi';
        $vendorPhone = $company?->contact_number ?: ($bill->vendor?->phone ?: '0331-2449892');
        $vendorEmail = $company?->email ?: 'info@company.com';
        $vendorNtn = $company?->ntn ?: ($bill->ntn_number ?: ($bill->vendor?->tax_number ?: '3437101-0'));
        $vendorStrn = $company?->gst ?: ($bill->strn_number ?: ($bill->vendor?->strn_number ?: '3277876289057'));
        $vendorSst = $company?->sst ?: ($bill->sst_number ?: 'S3437101-2');
        $vendorNo = $company?->vendor_no ?: ($bill->agpr_vendor_no ?: '30368984');

        // Customer Details
        $customerName = $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'The Directorate of Transit Trade Karachi';
        $customerAddress = $bill->department ? ($bill->department->city ?: 'Karachi') : 'Karachi';
        $customerDept = $bill->department ? $bill->department->name : 'Department of Stores';

        // Line Items
        $items = [];
        $grossTotal = 0;
        foreach ($bill->items as $item) {
            $qty = (float) $item->quantity;
            $basePrice = (float) $item->unit_price;
            $unitPrice = round($basePrice * $factor, 2);
            $total = round($qty * $unitPrice, 2);
            $grossTotal += $total;

            $items[] = [
                'name' => $item->item_name,
                'qty' => $qty,
                'unit' => $item->unit ?: 'Nos',
                'base_price' => $basePrice,
                'unit_price' => $unitPrice,
                'total_price' => $total,
            ];
        }

        $gstRate = (float) ($bill->gst_rate > 0 ? $bill->gst_rate : 18.00);
        $gstAmount = round($grossTotal * ($gstRate / 100), 2);
        $taxDeduction = (float) ($bill->tax_deduction ?? 0);
        $grandTotal = max(0, $grossTotal + $gstAmount - $taxDeduction);

        return view('bills.invoice_design', compact(
            'bill', 'company', 'allCompanies', 'templateOptions', 'activeTemplate', 'markupPercent',
            'vendorName', 'vendorAddress', 'vendorPhone', 'vendorEmail', 'vendorNtn', 'vendorStrn', 'vendorSst', 'vendorNo',
            'customerName', 'customerAddress', 'customerDept',
            'items', 'grossTotal', 'gstRate', 'gstAmount', 'taxDeduction', 'grandTotal'
        ));
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isEvaluator()) {
            abort(403, 'Only administration or department evaluators can change bill status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,verified_by_dept,approved,paid,rejected',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'paid') {
            $updateData['paid_at'] = now();
            $updateData['payment_reference'] = $validated['payment_reference'] ?? ('PAY-' . strtoupper(Str::random(8)));
        }

        $oldStatus = $bill->status;
        $bill->update($updateData);

        \App\Models\ActivityLog::record(
            action: 'status_changed',
            module: 'Bills',
            description: "Updated Bill #{$bill->bill_number} status from [{$oldStatus}] to [{$validated['status']}].",
            subject: $bill,
            properties: [
                'bill_number' => $bill->bill_number,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'payment_reference' => $updateData['payment_reference'] ?? null,
            ]
        );

        \App\Services\NotificationService::billStatusChanged($bill, $oldStatus, $validated['status']);

        return back()->with('success', 'Bill status updated to: ' . ucfirst(str_replace('_', ' ', $validated['status'])));
    }

    /**
     * Daily Billing Report - Filter by Date, Quarter, Head, Cost Center, Vendor & Department
     */
    public function dailyReport(Request $request)
    {
        $user = Auth::user();
        $query = Bill::with(['department', 'company', 'vendor', 'items']);

        if ($user && $user->isVendor()) {
            $query->where('vendor_id', $user->id);
        }

        // Single date filter (e.g. 20th, 21st, 22nd...) or Date Range
        $selectedDate = $request->get('date');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if ($request->filled('date')) {
            $query->whereDate('invoice_date', $selectedDate);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('invoice_date', [$dateFrom, $dateTo]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $dateFrom);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $dateTo);
        }

        // Quarter filter
        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }

        // Department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Company / Vendor filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Search Keyword
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('bill_number', 'like', "%{$s}%")
                  ->orWhere('head_code', 'like', "%{$s}%")
                  ->orWhere('head_description', 'like', "%{$s}%")
                  ->orWhere('head_of_account', 'like', "%{$s}%")
                  ->orWhere('cost_center', 'like', "%{$s}%")
                  ->orWhere('remarks', 'like', "%{$s}%")
                  ->orWhereHas('company', fn($cq) => $cq->where('company_name', 'like', "%{$s}%"))
                  ->orWhereHas('department', fn($dq) => $dq->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"));
            });
        }

        $allBills = (clone $query)->orderBy('invoice_date', 'desc')->orderBy('id', 'desc')->get();

        $stats = [
            'total_count' => $allBills->count(),
            'total_amount' => $allBills->sum(fn($b) => (float)($b->net_amount ?: $b->gross_amount)),
            'dispatched_count' => $allBills->whereNotNull('dispatch_date')->count(),
            'pending_dispatch_count' => $allBills->whereNull('dispatch_date')->count(),
        ];

        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $companies = \App\Models\Company::where('status', 'active')->orderBy('company_name')->get();
        $quarters = ['1st Qtr', '2nd Qtr', '3rd Qtr', '4th Qtr'];

        return view('bills.daily_report', compact('allBills', 'stats', 'departments', 'companies', 'quarters', 'selectedDate', 'dateFrom', 'dateTo'));
    }

    /**
     * Export Daily Billing Report to Excel (.xls) or CSV
     */
    public function exportDailyReport(Request $request)
    {
        $user = Auth::user();
        $query = Bill::with(['department', 'company', 'vendor']);

        if ($user && $user->isVendor()) {
            $query->where('vendor_id', $user->id);
        }

        if ($request->filled('date')) {
            $query->whereDate('invoice_date', $request->date);
        } elseif ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('invoice_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('bill_number', 'like', "%{$s}%")
                  ->orWhere('head_code', 'like', "%{$s}%")
                  ->orWhere('head_description', 'like', "%{$s}%")
                  ->orWhere('head_of_account', 'like', "%{$s}%")
                  ->orWhere('cost_center', 'like', "%{$s}%")
                  ->orWhere('remarks', 'like', "%{$s}%");
            });
        }

        $bills = $query->orderBy('invoice_date', 'asc')->orderBy('id', 'asc')->get();

        $format = strtolower($request->get('format', 'xls'));
        $dateSuffix = $request->filled('date') ? $request->date : ($request->filled('date_from') ? ($request->date_from . '_to_' . $request->date_to) : date('d-M-Y'));
        $fileName = 'Daily_Billing_Report_' . $dateSuffix . '.' . ($format === 'csv' ? 'csv' : 'xls');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ];

            return response()->stream(function () use ($bills) {
                $file = fopen('php://output', 'w');
                // Output UTF-8 BOM so Excel opens it with proper encoding
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Exact Columns Matching Image
                fputcsv($file, [
                    'Sr #',
                    'Bill CreateDate',
                    'Bill No',
                    'Quarter',
                    'Head of Account',
                    'Head Description',
                    'Cost Center',
                    'Department Name',
                    'GST/SST',
                    'Amount',
                    'Vendor Name',
                    'Bill Dispatch Date',
                    'Remarks',
                ]);

                $sr = 1;
                $totalAmount = 0;
                foreach ($bills as $b) {
                    $amt = (float) ($b->net_amount ?: $b->gross_amount);
                    $totalAmount += $amt;
                    fputcsv($file, [
                        $sr++,
                        $b->formatted_create_date,
                        $b->bill_number,
                        $b->quarter_display,
                        $b->head_code_display,
                        $b->head_description_display,
                        $b->cost_center_code_display,
                        $b->department_name_display,
                        $b->gst_display,
                        number_format($amt),
                        $b->vendor_name_display,
                        $b->formatted_dispatch_date,
                        $b->remarks ?: '',
                    ]);
                }

                // Total Row
                fputcsv($file, [
                    'Total',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    number_format($totalAmount),
                    '',
                    '',
                    '',
                ]);

                fclose($file);
            }, 200, $headers);
        }

        // HTML Excel Spreadsheet with styled peach header #f5c6a5, borders & exact cell alignment
        return response()->view('bills.export_excel', compact('bills'), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Quick Update Dispatch Date & Remarks for a Bill
     */
    public function quickUpdateDispatch(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'dispatch_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:255',
            'quarter' => 'nullable|string|max:50',
            'head_code' => 'nullable|string|max:50',
            'head_description' => 'nullable|string|max:255',
        ]);

        $bill->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bill dispatch details updated successfully.',
                'bill' => $bill,
            ]);
        }

        return back()->with('success', "Bill #{$bill->bill_number} dispatch & remarks updated successfully.");
    }
}
