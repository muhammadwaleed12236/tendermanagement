<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of all vendors with search, filters and metrics
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Vendor::query()->with('creator')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'vendors' => $query->take(100)->get(),
            ]);
        }

        $vendors = $query->paginate(15)->withQueryString();

        // Key Metrics
        $totalVendors = Vendor::count();
        $activeVendors = Vendor::where('status', 'active')->count();
        $totalOpeningBalance = (float) Vendor::sum('opening_balance');
        $totalCurrentBalance = (float) Vendor::sum(DB::raw("COALESCE(current_balance, opening_balance)"));

        return view('vendors.index', compact(
            'vendors',
            'search',
            'status',
            'totalVendors',
            'activeVendors',
            'totalOpeningBalance',
            'totalCurrentBalance'
        ));
    }

    /**
     * Show form to create a new vendor
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created vendor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'address'         => 'nullable|string|max:1000',
            'opening_balance' => 'nullable|numeric|min:0',
            'status'          => 'nullable|in:active,inactive',
        ]);

        $openingBalance = isset($validated['opening_balance']) ? (float) $validated['opening_balance'] : 0.00;

        $vendor = Vendor::create([
            'name'            => trim($validated['name']),
            'phone'           => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'address'         => !empty($validated['address']) ? trim($validated['address']) : null,
            'opening_balance' => $openingBalance,
            'current_balance' => $openingBalance,
            'status'          => $validated['status'] ?? 'active',
            'created_by'      => Auth::id(),
        ]);

        ActivityLog::record(
            action: 'created',
            module: 'Vendors',
            description: "Registered new vendor [{$vendor->name}] with Opening Balance PKR " . number_format($vendor->opening_balance, 2) . ".",
            subject: $vendor
        );

        return redirect()->route('vendors.index')->with('success', "Vendor '{$vendor->name}' created successfully!");
    }

    /**
     * Display the specified vendor profile
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['creator', 'purchases.items.product']);
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show form for editing the specified vendor
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'address'         => 'nullable|string|max:1000',
            'opening_balance' => 'nullable|numeric|min:0',
            'status'          => 'required|in:active,inactive',
        ]);

        $openingBalance = isset($validated['opening_balance']) ? (float) $validated['opening_balance'] : $vendor->opening_balance;

        $vendor->update([
            'name'            => trim($validated['name']),
            'phone'           => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'address'         => !empty($validated['address']) ? trim($validated['address']) : null,
            'opening_balance' => $openingBalance,
            'status'          => $validated['status'],
        ]);

        ActivityLog::record(
            action: 'updated',
            module: 'Vendors',
            description: "Updated vendor [{$vendor->name}] details.",
            subject: $vendor
        );

        return redirect()->route('vendors.index')->with('success', "Vendor '{$vendor->name}' updated successfully!");
    }

    /**
     * Remove the specified vendor
     */
    public function destroy(Vendor $vendor)
    {
        $vendorName = $vendor->name;

        ActivityLog::record(
            action: 'deleted',
            module: 'Vendors',
            description: "Deleted vendor [{$vendorName}] (Opening Balance: PKR " . number_format($vendor->opening_balance, 2) . ").",
            subject: $vendor
        );

        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', "Vendor '{$vendorName}' deleted successfully!");
    }
}
