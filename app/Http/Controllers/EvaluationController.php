<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function store(Request $request, Bid $bid)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isEvaluator()) {
            abort(403, 'Unauthorized action. Only evaluators or administrators can score bids.');
        }

        $validated = $request->validate([
            'technical_score' => 'required|integer|min:0|max:100',
            'financial_score' => 'required|integer|min:0|max:100',
            'remarks' => 'nullable|string|max:1000',
        ]);

        Evaluation::updateOrCreate(
            [
                'bid_id' => $bid->id,
                'evaluator_id' => $user->id,
            ],
            [
                'technical_score' => $validated['technical_score'],
                'financial_score' => $validated['financial_score'],
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        // Update bid status to under_review if submitted
        if ($bid->status === 'submitted') {
            $bid->update(['status' => 'under_review']);
        }

        return back()->with('success', 'Evaluation score and remarks saved successfully.');
    }
}
