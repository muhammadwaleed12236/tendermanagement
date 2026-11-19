<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display listing of configured companies / suppliers
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Company::query()->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('vendor_no', 'like', "%{$search}%")
                  ->orWhere('ntn', 'like', "%{$search}%")
                  ->orWhere('gst', 'like', "%{$search}%")
                  ->orWhere('sst', 'like', "%{$search}%");
            });
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'companies' => Company::where('status', 'active')->orderBy('company_name')->get(),
            ]);
        }

        $companies = $query->paginate(15)->withQueryString();
        $totalCount = Company::count();
        $templateOptions = Company::templateOptions();
        $bodyStyleOptions = Company::bodyStyleOptions();

        return view('companies.index', compact('companies', 'search', 'totalCount', 'templateOptions', 'bodyStyleOptions'));
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'vendor_no' => 'nullable|string|max:100',
            'ntn' => 'nullable|string|max:100',
            'gst' => 'nullable|string|max:100',
            'sst' => 'nullable|string|max:100',
            'invoice_template' => 'nullable|string|max:50',
            'body_style' => 'nullable|string|in:' . implode(',', array_keys(Company::bodyStyleOptions())),
            'total_layout' => 'nullable|string|in:' . implode(',', array_keys(Company::totalLayoutOptions())),
            'show_unit_column' => 'nullable|boolean',
            'show_sr_column' => 'nullable|boolean',
            'address' => 'nullable|string|max:1000',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            Company::where('is_default', true)->update(['is_default' => false]);
        } elseif (Company::count() === 0) {
            $isDefault = true;
        }

        $company = Company::create([
            'company_name' => trim($validated['company_name']),
            'vendor_no' => $validated['vendor_no'] ?? null,
            'ntn' => $validated['ntn'] ?? null,
            'gst' => $validated['gst'] ?? null,
            'sst' => $validated['sst'] ?? null,
            'invoice_template' => $validated['invoice_template'] ?? 'navy_wave',
            'body_style' => $validated['body_style'] ?? 'corporate_navy',
            'total_layout' => $validated['total_layout'] ?? 'auto',
            'show_unit_column' => $request->boolean('show_unit_column', true),
            'show_sr_column' => $request->boolean('show_sr_column', true),
            'address' => $validated['address'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => 'active',
            'is_default' => $isDefault,
            'created_by' => Auth::id(),
        ]);

        \App\Models\ActivityLog::record(
            action: 'created',
            module: 'Companies',
            description: "Registered company [{$company->company_name}] (NTN: {$company->ntn}, Vendor #: {$company->vendor_no}).",
            subject: $company,
            properties: ['company_name' => $company->company_name, 'ntn' => $company->ntn]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Company '{$company->company_name}' registered successfully!",
                'company' => $company,
            ]);
        }

        return redirect()->route('companies.index')->with('success', "Company '{$company->company_name}' created successfully!");
    }

    /**
     * Update an existing company
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'vendor_no' => 'nullable|string|max:100',
            'ntn' => 'nullable|string|max:100',
            'gst' => 'nullable|string|max:100',
            'sst' => 'nullable|string|max:100',
            'invoice_template' => 'nullable|string|max:50',
            'body_style' => 'nullable|string|in:' . implode(',', array_keys(Company::bodyStyleOptions())),
            'total_layout' => 'nullable|string|in:' . implode(',', array_keys(Company::totalLayoutOptions())),
            'show_unit_column' => 'nullable|boolean',
            'show_sr_column' => 'nullable|boolean',
            'address' => 'nullable|string|max:1000',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'is_default' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault && !$company->is_default) {
            Company::where('is_default', true)->update(['is_default' => false]);
        }

        $company->update([
            'company_name' => trim($validated['company_name']),
            'vendor_no' => $validated['vendor_no'] ?? null,
            'ntn' => $validated['ntn'] ?? null,
            'gst' => $validated['gst'] ?? null,
            'sst' => $validated['sst'] ?? null,
            'invoice_template' => $validated['invoice_template'] ?? ($company->invoice_template ?: 'navy_wave'),
            'body_style' => $validated['body_style'] ?? ($company->body_style ?: 'corporate_navy'),
            'total_layout' => $validated['total_layout'] ?? ($company->total_layout ?: 'auto'),
            'show_unit_column' => $request->has('show_unit_column') ? $request->boolean('show_unit_column') : $company->show_unit_column,
            'show_sr_column' => $request->has('show_sr_column') ? $request->boolean('show_sr_column') : $company->show_sr_column,
            'address' => $validated['address'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? $company->status,
            'is_default' => $isDefault,
        ]);

        \App\Models\ActivityLog::record(
            action: 'updated',
            module: 'Companies',
            description: "Updated company details for [{$company->company_name}].",
            subject: $company
        );

        return redirect()->route('companies.index')->with('success', "Company '{$company->company_name}' updated successfully!");
    }

    /**
     * Set a company as the default company
     */
    public function setDefault(Company $company)
    {
        Company::where('is_default', true)->update(['is_default' => false]);
        $company->update(['is_default' => true]);

        \App\Models\ActivityLog::record(
            action: 'updated',
            module: 'Companies',
            description: "Set [{$company->company_name}] as the primary default company.",
            subject: $company
        );

        return back()->with('success', "'{$company->company_name}' is now set as the primary default company!");
    }

    /**
     * Delete a company
     */
    public function destroy(Company $company)
    {
        $name = $company->company_name;
        $company->delete();

        \App\Models\ActivityLog::record(
            action: 'deleted',
            module: 'Companies',
            description: "Deleted company [{$name}].",
            properties: ['company_name' => $name]
        );

        return redirect()->route('companies.index')->with('success', "Company '{$name}' deleted successfully!");
    }

    /**
     * Continuous Scroll Invoice Designs Showcase & Company Assignment Gallery
     */
    public function invoiceGallery(Request $request)
    {
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();
        $templateOptions = Company::templateOptions();

        // Group companies by their currently assigned invoice template
        $assignedCompanies = Company::all()->groupBy('invoice_template');

        // Fetch the latest bill for realistic live invoice preview
        $bill = \App\Models\Bill::with('items')->latest()->first();

        if ($bill) {
            $vendorName = $bill->vendor_name ?: 'M/S. COMZON';
            $vendorAddress = $bill->company?->address ?: 'Office # 1/2 Block-6, PECHS Nursery, Karachi';
            $vendorPhone = $bill->company?->contact_number ?: '+92 331 2449892';
            $vendorEmail = $bill->company?->email ?: 'info@comzon.com.pk';
            $vendorNtn = $bill->company?->ntn ?: ($bill->vendor_ntn ?: '3437101-0');
            $vendorStrn = $bill->company?->gst ?: ($bill->vendor_strn ?: '3277876289057');
            $vendorSst = $bill->company?->sst ?: ($bill->vendor_sst ?: 'S3437101-0');
            $vendorNo = $bill->company?->vendor_no ?: 'AGPR/TR-9821';

            $customerName = $bill->customer_name ?: 'The Directorate of Transit Trade Karachi';
            $customerAddress = $bill->customer_address ?: 'Custom House, Karachi';
            $customerDept = $bill->department ?: 'Transit Trade & Commerce';

            $items = [];
            $grossTotal = 0;
            foreach ($bill->items as $item) {
                $qty = (float) ($item->quantity ?: 1);
                $basePrice = (float) $item->unit_price;
                $unitPrice = $basePrice;
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

            if (empty($items)) {
                $items = [
                    ['name' => 'Computer Paper Legal Size, 80 Gsm', 'qty' => 10, 'unit' => 'Ream', 'unit_price' => 1850, 'total_price' => 18500],
                    ['name' => 'Computer Paper A4 Size, 80 Gsm', 'qty' => 30, 'unit' => 'Ream', 'unit_price' => 1450, 'total_price' => 43500],
                    ['name' => 'HP LaserJet Printer Toner 151A Original', 'qty' => 1, 'unit' => 'Nos', 'unit_price' => 24000, 'total_price' => 24000],
                    ['name' => 'HP LaserJet Printer Toner 17A Original', 'qty' => 1, 'unit' => 'Nos', 'unit_price' => 23800, 'total_price' => 23800],
                ];
                $grossTotal = 110000;
            }

            $gstRate = (float) ($bill->gst_rate > 0 ? $bill->gst_rate : 18.00);
            $gstAmount = round($grossTotal * ($gstRate / 100), 2);
            $taxDeduction = (float) ($bill->tax_deduction ?? 0);
            $grandTotal = max(0, $grossTotal + $gstAmount - $taxDeduction);
        } else {
            $bill = (object)[
                'id' => 1,
                'bill_number' => 'INV-2026-0045',
                'invoice_date' => now(),
                'notes' => 'Official government supply tender order fulfillment',
            ];
            $vendorName = 'M/S. COMZON';
            $vendorAddress = 'Office # 1/2 Block-6, PECHS Nursery, Karachi';
            $vendorPhone = '+92 331 2449892';
            $vendorEmail = 'info@comzon.com.pk';
            $vendorNtn = '3437101-0';
            $vendorStrn = '3277876289057';
            $vendorSst = 'S3437101-0';
            $vendorNo = 'AGPR/TR-9821';
            $customerName = 'The Directorate of Transit Trade Karachi';
            $customerAddress = 'Custom House, Karachi';
            $customerDept = 'Transit Trade & Commerce';
            $items = [
                ['name' => 'Computer Paper Legal Size, 80 Gsm', 'qty' => 10, 'unit' => 'Ream', 'unit_price' => 1850, 'total_price' => 18500],
                ['name' => 'Computer Paper A4 Size, 80 Gsm', 'qty' => 30, 'unit' => 'Ream', 'unit_price' => 1450, 'total_price' => 43500],
                ['name' => 'HP LaserJet Printer Toner 151A Original', 'qty' => 1, 'unit' => 'Nos', 'unit_price' => 24000, 'total_price' => 24000],
                ['name' => 'HP LaserJet Printer Toner 17A Original', 'qty' => 1, 'unit' => 'Nos', 'unit_price' => 23800, 'total_price' => 23800],
            ];
            $grossTotal = 110000;
            $gstRate = 18.00;
            $gstAmount = 19800;
            $taxDeduction = 0;
            $grandTotal = 129800;
        }

        return view('companies.invoice_gallery', compact(
            'companies', 'templateOptions', 'assignedCompanies', 'bill',
            'vendorName', 'vendorAddress', 'vendorPhone', 'vendorEmail', 'vendorNtn', 'vendorStrn', 'vendorSst', 'vendorNo',
            'customerName', 'customerAddress', 'customerDept',
            'items', 'grossTotal', 'gstRate', 'gstAmount', 'taxDeduction', 'grandTotal'
        ));
    }

    /**
     * Assign a specific invoice template to a company
     */
    public function assignTemplate(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'invoice_template' => 'required|string|in:' . implode(',', array_keys(Company::templateOptions())),
        ]);

        $company = Company::findOrFail($validated['company_id']);
        $company->update([
            'invoice_template' => $validated['invoice_template']
        ]);

        $tplOptions = Company::templateOptions();
        $tplName = $tplOptions[$validated['invoice_template']]['name'] ?? $validated['invoice_template'];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Pattern '{$tplName}' successfully assigned to {$company->company_name}!",
                'company_id' => $company->id,
                'company_name' => $company->company_name,
                'template' => $validated['invoice_template'],
                'template_name' => $tplName,
            ]);
        }

        return back()->with('success', "Invoice Pattern '{$tplName}' successfully assigned to {$company->company_name}!");
    }

    /**
     * Overview index of all companies and their letterhead setup
     */
    public function allLetterheads(Request $request)
    {
        $companies = Company::orderBy('company_name')->get();
        $totalCount = $companies->count();
        $customCount = $companies->filter(fn($c) => $c->hasCustomLetterhead())->count();

        return view('companies.letterheads_index', compact('companies', 'totalCount', 'customCount'));
    }

    /**
     * Dedicated Letterhead & Inner Body Designer / Manager for a specific company
     */
    public function letterhead(Request $request, Company $company)
    {
        $allCompanies = Company::orderBy('company_name')->get();
        $bill = \App\Models\Bill::with('items')->latest()->first();
        $bodyStyleOptions = Company::bodyStyleOptions();
        $totalLayoutOptions = Company::totalLayoutOptions();

        $previewItems = [];
        $previewGross = 0;
        if ($bill && $bill->items->count() > 0) {
            foreach ($bill->items as $item) {
                $qty = (float) $item->quantity;
                $rate = (float) $item->unit_price;
                $tot = round($qty * $rate, 2);
                $previewGross += $tot;
                $previewItems[] = [
                    'name' => $item->item_name,
                    'qty' => $qty,
                    'unit' => $item->unit ?: 'Nos',
                    'rate' => $rate,
                    'total' => $tot,
                ];
            }
        }

        if (empty($previewItems)) {
            $previewItems = [
                ['name' => 'Computer Paper Legal Size, 80 Gsm', 'qty' => 10, 'unit' => 'Ream', 'rate' => 1850.00, 'total' => 18500.00],
                ['name' => 'HP LaserJet Printer Toner 151A Original', 'qty' => 2, 'unit' => 'Nos', 'rate' => 24000.00, 'total' => 48000.00],
                ['name' => 'Heavy Duty Office Executive Mesh Chair', 'qty' => 1, 'unit' => 'Nos', 'rate' => 24500.00, 'total' => 24500.00],
            ];
            $previewGross = 91000.00;
        }

        $gstRate = (float) ($bill?->gst_rate > 0 ? $bill->gst_rate : 18.00);
        $previewGst = round($previewGross * ($gstRate / 100), 2);
        $previewGrandTotal = $previewGross + $previewGst;

        return view('companies.letterhead', compact(
            'company', 'allCompanies', 'bill', 'bodyStyleOptions', 'totalLayoutOptions',
            'previewItems', 'previewGross', 'gstRate', 'previewGst', 'previewGrandTotal'
        ));
    }

    /**
     * Save uploaded letterhead files, dimensions, margins and inner body defaults
     */
    public function updateLetterhead(Request $request, Company $company)
    {
        $validated = $request->validate([
            'letterhead_type' => 'required|in:system_default,header_footer,full_page',
            'body_style' => 'nullable|string|in:' . implode(',', array_keys(Company::bodyStyleOptions())),
            'total_layout' => 'nullable|string|in:' . implode(',', array_keys(Company::totalLayoutOptions())),
            'show_unit_column' => 'nullable|boolean',
            'show_sr_column' => 'nullable|boolean',
            'header_image_file' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'footer_image_file' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'full_letterhead_image_file' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:10240',
            'header_height' => 'nullable|integer|min:30|max:500',
            'footer_height' => 'nullable|integer|min:20|max:400',
            'body_top_margin' => 'nullable|integer|min:20|max:500',
            'body_bottom_margin' => 'nullable|integer|min:20|max:400',
            'body_side_margin' => 'nullable|integer|min:10|max:150',
            'show_on_print' => 'nullable|boolean',
            'default_subject' => 'nullable|string|max:500',
            'default_terms' => 'nullable|string|max:5000',
            'signatory_title' => 'nullable|string|max:255',
        ]);

        $data = [
            'letterhead_type' => $validated['letterhead_type'],
            'body_style' => $validated['body_style'] ?? ($company->body_style ?: 'corporate_navy'),
            'total_layout' => $validated['total_layout'] ?? ($company->total_layout ?: 'auto'),
            'show_unit_column' => $request->boolean('show_unit_column', true),
            'show_sr_column' => $request->boolean('show_sr_column', true),
            'header_height' => $validated['header_height'] ?? 140,
            'footer_height' => $validated['footer_height'] ?? 80,
            'body_top_margin' => $validated['body_top_margin'] ?? 150,
            'body_bottom_margin' => $validated['body_bottom_margin'] ?? 90,
            'body_side_margin' => $validated['body_side_margin'] ?? 45,
            'show_on_print' => $request->boolean('show_on_print', true),
            'default_subject' => $validated['default_subject'] ?? null,
            'default_terms' => $validated['default_terms'] ?? null,
            'signatory_title' => $validated['signatory_title'] ?? null,
        ];

        // Store Header image
        if ($request->hasFile('header_image_file')) {
            if ($company->header_image && Storage::disk('public')->exists($company->header_image)) {
                Storage::disk('public')->delete($company->header_image);
            }
            $data['header_image'] = $request->file('header_image_file')->store('letterheads', 'public');
        }

        // Store Footer image
        if ($request->hasFile('footer_image_file')) {
            if ($company->footer_image && Storage::disk('public')->exists($company->footer_image)) {
                Storage::disk('public')->delete($company->footer_image);
            }
            $data['footer_image'] = $request->file('footer_image_file')->store('letterheads', 'public');
        }

        // Store Full Page background image
        if ($request->hasFile('full_letterhead_image_file')) {
            if ($company->full_letterhead_image && Storage::disk('public')->exists($company->full_letterhead_image)) {
                Storage::disk('public')->delete($company->full_letterhead_image);
            }
            $data['full_letterhead_image'] = $request->file('full_letterhead_image_file')->store('letterheads', 'public');
        }

        $company->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Letterhead settings for {$company->company_name} saved successfully!",
                'company' => $company->fresh(),
                'header_image_url' => $company->header_image_url,
                'footer_image_url' => $company->footer_image_url,
                'full_letterhead_image_url' => $company->full_letterhead_image_url,
            ]);
        }

        return redirect()->route('companies.letterhead', $company)->with('success', "Letterhead & inner body settings for {$company->company_name} saved successfully!");
    }

    /**
     * Remove an uploaded letterhead image (header, footer, or full page)
     */
    public function removeLetterheadImage(Request $request, Company $company)
    {
        $type = $request->input('image_type'); // header, footer, full_page
        if ($type === 'header' && $company->header_image) {
            Storage::disk('public')->delete($company->header_image);
            $company->update(['header_image' => null]);
        } elseif ($type === 'footer' && $company->footer_image) {
            Storage::disk('public')->delete($company->footer_image);
            $company->update(['footer_image' => null]);
        } elseif ($type === 'full_page' && $company->full_letterhead_image) {
            Storage::disk('public')->delete($company->full_letterhead_image);
            $company->update(['full_letterhead_image' => null]);
        }

        return back()->with('success', "Letterhead image removed successfully.");
    }
}
