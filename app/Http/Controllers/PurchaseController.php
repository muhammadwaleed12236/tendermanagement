<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Listing of all purchases with filters and financial statistics
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $vendorId = $request->query('vendor_id');
        $status = $request->query('payment_status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Purchase::with(['vendor', 'creator', 'items.product'])->latest('purchase_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        if ($status && in_array($status, ['paid', 'unpaid'])) {
            $query->where('payment_status', $status);
        }

        if ($fromDate) {
            $query->whereDate('purchase_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('purchase_date', '<=', $toDate);
        }

        $purchases = $query->paginate(15)->withQueryString();

        // Summary Statistics
        $totalPurchasesCount = Purchase::count();
        $totalPurchasedValue = (float) Purchase::sum('total_amount');
        $totalPaidValue = (float) Purchase::where('payment_status', 'paid')->sum('total_amount');
        $totalUnpaidValue = (float) Purchase::where('payment_status', 'unpaid')->sum('total_amount');

        $vendors = Vendor::orderBy('name')->get(['id', 'name']);

        return view('purchases.index', compact(
            'purchases',
            'search',
            'vendorId',
            'status',
            'fromDate',
            'toDate',
            'totalPurchasesCount',
            'totalPurchasedValue',
            'totalPaidValue',
            'totalUnpaidValue',
            'vendors'
        ));
    }

    /**
     * Show form to record a new purchase
     */
    public function create()
    {
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        $products = Product::orderBy('description')->get(['id', 'description', 'unit', 'default_price', 'item_code']);

        // Generate suggested purchase number: PUR-YYYYMMDD-XXXX
        $todayPrefix = 'PUR-' . date('Ymd') . '-';
        $todayCount = Purchase::where('purchase_number', 'like', "{$todayPrefix}%")->count() + 1;
        $suggestedNumber = $todayPrefix . str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('vendors', 'products', 'suggestedNumber'));
    }

    /**
     * Store new purchase and line items with optional GST/SST
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_number' => 'required|string|max:50|unique:purchases,purchase_number',
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'payment_status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_description' => 'required|string|max:255',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.sst_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $subtotalSum = 0;
            $gstSum = 0;
            $sstSum = 0;
            $totalSum = 0;

            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $base = round($qty * $price, 2);

                $gstPct = isset($item['gst_percent']) && $item['gst_percent'] !== '' ? (float) $item['gst_percent'] : 0.00;
                $sstPct = isset($item['sst_percent']) && $item['sst_percent'] !== '' ? (float) $item['sst_percent'] : 0.00;

                $gstAmt = $gstPct > 0 ? round($base * ($gstPct / 100), 2) : 0.00;
                $sstAmt = $sstPct > 0 ? round($base * ($sstPct / 100), 2) : 0.00;
                $lineTotal = round($base + $gstAmt + $sstAmt, 2);

                $subtotalSum += $base;
                $gstSum += $gstAmt;
                $sstSum += $sstAmt;
                $totalSum += $lineTotal;

                $itemsData[] = [
                    'product_id' => $item['product_id'] ?: null,
                    'item_description' => trim($item['item_description']),
                    'unit' => !empty($item['unit']) ? trim($item['unit']) : 'Nos',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'gst_percent' => $gstPct,
                    'gst_amount' => $gstAmt,
                    'sst_percent' => $sstPct,
                    'sst_amount' => $sstAmt,
                    'total_amount' => $lineTotal,
                ];
            }

            // Create purchase header
            $purchase = Purchase::create([
                'purchase_number' => trim($validated['purchase_number']),
                'vendor_id' => $validated['vendor_id'],
                'purchase_date' => $validated['purchase_date'],
                'payment_status' => $validated['payment_status'],
                'subtotal_amount' => $subtotalSum,
                'gst_amount' => $gstSum,
                'sst_amount' => $sstSum,
                'total_amount' => $totalSum,
                'notes' => !empty($validated['notes']) ? trim($validated['notes']) : null,
                'created_by' => Auth::id(),
            ]);

            // Save line items
            foreach ($itemsData as $row) {
                $purchase->items()->create($row);
            }

            // Recalculate Vendor Balance
            $vendor = Vendor::find($validated['vendor_id']);
            if ($vendor) {
                $vendor->recalculateBalance();
            }

            // Activity Log
            ActivityLog::record(
                action: 'created',
                module: 'Purchasing',
                description: "Recorded purchase [{$purchase->purchase_number}] from vendor [{$vendor->name}] for PKR " . number_format($purchase->total_amount, 2) . " (Status: " . ucfirst($purchase->payment_status) . ").",
                subject: $purchase
            );

            DB::commit();

            return redirect()->route('purchases.show', $purchase)->with('success', "Purchase '{$purchase->purchase_number}' recorded successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error recording purchase: ' . $e->getMessage());
        }
    }

    /**
     * Display purchase bill / invoice details
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['vendor', 'creator', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show form to edit purchase
     */
    public function edit(Purchase $purchase)
    {
        $purchase->load(['items.product', 'vendor']);
        $vendors = Vendor::where('status', 'active')->orWhere('id', $purchase->vendor_id)->orderBy('name')->get();
        $products = Product::orderBy('description')->get(['id', 'description', 'unit', 'default_price', 'item_code']);

        return view('purchases.edit', compact('purchase', 'vendors', 'products'));
    }

    /**
     * Update existing purchase record
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'purchase_number' => 'required|string|max:50|unique:purchases,purchase_number,' . $purchase->id,
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'payment_status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_description' => 'required|string|max:255',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.sst_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $oldVendorId = $purchase->vendor_id;

            $subtotalSum = 0;
            $gstSum = 0;
            $sstSum = 0;
            $totalSum = 0;

            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $base = round($qty * $price, 2);

                $gstPct = isset($item['gst_percent']) && $item['gst_percent'] !== '' ? (float) $item['gst_percent'] : 0.00;
                $sstPct = isset($item['sst_percent']) && $item['sst_percent'] !== '' ? (float) $item['sst_percent'] : 0.00;

                $gstAmt = $gstPct > 0 ? round($base * ($gstPct / 100), 2) : 0.00;
                $sstAmt = $sstPct > 0 ? round($base * ($sstPct / 100), 2) : 0.00;
                $lineTotal = round($base + $gstAmt + $sstAmt, 2);

                $subtotalSum += $base;
                $gstSum += $gstAmt;
                $sstSum += $sstAmt;
                $totalSum += $lineTotal;

                $itemsData[] = [
                    'product_id' => $item['product_id'] ?: null,
                    'item_description' => trim($item['item_description']),
                    'unit' => !empty($item['unit']) ? trim($item['unit']) : 'Nos',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'gst_percent' => $gstPct,
                    'gst_amount' => $gstAmt,
                    'sst_percent' => $sstPct,
                    'sst_amount' => $sstAmt,
                    'total_amount' => $lineTotal,
                ];
            }

            $purchase->update([
                'purchase_number' => trim($validated['purchase_number']),
                'vendor_id' => $validated['vendor_id'],
                'purchase_date' => $validated['purchase_date'],
                'payment_status' => $validated['payment_status'],
                'subtotal_amount' => $subtotalSum,
                'gst_amount' => $gstSum,
                'sst_amount' => $sstSum,
                'total_amount' => $totalSum,
                'notes' => !empty($validated['notes']) ? trim($validated['notes']) : null,
            ]);

            // Replace line items
            $purchase->items()->delete();
            foreach ($itemsData as $row) {
                $purchase->items()->create($row);
            }

            // Recalculate balance for old vendor if vendor changed
            if ($oldVendorId != $validated['vendor_id']) {
                $oldVendor = Vendor::find($oldVendorId);
                if ($oldVendor) {
                    $oldVendor->recalculateBalance();
                }
            }

            // Recalculate current vendor balance
            $vendor = Vendor::find($validated['vendor_id']);
            if ($vendor) {
                $vendor->recalculateBalance();
            }

            // Activity Log
            ActivityLog::record(
                action: 'updated',
                module: 'Purchasing',
                description: "Updated purchase [{$purchase->purchase_number}] from vendor [{$vendor->name}].",
                subject: $purchase
            );

            DB::commit();

            return redirect()->route('purchases.show', $purchase)->with('success', "Purchase '{$purchase->purchase_number}' updated successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating purchase: ' . $e->getMessage());
        }
    }

    /**
     * Delete purchase record
     */
    public function destroy(Purchase $purchase)
    {
        $vendor = $purchase->vendor;
        $purchaseNumber = $purchase->purchase_number;

        DB::beginTransaction();
        try {
            $purchase->items()->delete();
            $purchase->delete();

            if ($vendor) {
                $vendor->recalculateBalance();
            }

            ActivityLog::record(
                action: 'deleted',
                module: 'Purchasing',
                description: "Deleted purchase [{$purchaseNumber}].",
                subject: null
            );

            DB::commit();

            return redirect()->route('purchases.index')->with('success', "Purchase '{$purchaseNumber}' deleted successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    /**
     * Quick toggle payment status between paid and unpaid
     */
    public function togglePaymentStatus(Purchase $purchase)
    {
        $newStatus = $purchase->payment_status === 'paid' ? 'unpaid' : 'paid';

        DB::beginTransaction();
        try {
            $purchase->update(['payment_status' => $newStatus]);

            if ($purchase->vendor) {
                $purchase->vendor->recalculateBalance();
            }

            ActivityLog::record(
                action: 'updated',
                module: 'Purchasing',
                description: "Changed payment status of purchase [{$purchase->purchase_number}] to [{$newStatus}].",
                subject: $purchase
            );

            DB::commit();

            return back()->with('success', "Purchase status marked as " . ucfirst($newStatus) . ".");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error toggling status: ' . $e->getMessage());
        }
    }
}
