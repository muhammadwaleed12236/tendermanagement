<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        $user = Auth::user();

        if (!$user->isVendor()) {
            return back()->with('error', 'Only registered vendors can submit bids.');
        }

        if (!$tender->isOpen()) {
            return back()->with('error', 'This tender is currently not accepting submissions.');
        }

        if (Bid::where('tender_id', $tender->id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already submitted a bid for this tender.');
        }

        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:1',
            'proposal_description' => 'required|string|min:20',
            'proposal_document' => 'nullable|file|mimes:pdf,docx,zip|max:20480',
        ]);

        $docPath = null;
        if ($request->hasFile('proposal_document')) {
            $docPath = $request->file('proposal_document')->store('bid_documents', 'public');
        }

        Bid::create([
            'tender_id' => $tender->id,
            'user_id' => $user->id,
            'bid_amount' => $validated['bid_amount'],
            'proposal_description' => $validated['proposal_description'],
            'document_path' => $docPath,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('tenders.show', $tender)->with('success', 'Your bid proposal has been submitted successfully.');
    }

    public function myBids()
    {
        $user = Auth::user();
        $bids = Bid::where('user_id', $user->id)
            ->with(['tender', 'evaluations'])
            ->latest()
            ->paginate(10);

        return view('bids.my-bids', compact('bids'));
    }

    public function updateStatus(Request $request, Bid $bid)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,accepted,rejected',
        ]);

        $bid->update(['status' => $validated['status']]);

        // If bid is accepted, we can mark tender as awarded
        if ($validated['status'] === 'accepted') {
            $bid->tender->update(['status' => 'awarded']);
        }

        return back()->with('success', 'Bid status updated to ' . ucfirst($validated['status']));
    }
}
