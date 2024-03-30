<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Employer;
use App\Models\Offer;
use App\Models\Candidacy;


class CandidacyController extends Controller
{
    // fetch candidacies of an employer
    public function getEmployerCandidacies(Request $request, $offerId)
    {
        $user = Auth::user();
        $employer = Employer::where('user_id', $user->id)->first();

        if (!$employer) {
            return response()->json(['message' => 'Employer not found'], 404);
        }

        $offer = Offer::where('id', $offerId)
              ->where('employer_id', $employer->id)
              ->first();

        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }

        $perPage = $request->input('perPage', 10);
        $candidacies = Candidacy::where('offer_id', $offer->id)
            ->with(['candidate', 'offer'])->paginate($perPage);

        return response()->json($candidacies);
    }

    // Create new candidacy
    public function createCandidacy(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'offer_id' => 'required|exists:offers,id',
        ]);

        $validated['status'] = Candidacy::STATUS_APPLIED;
        $validated['candidate_id'] = $user->id;

        $candidacy = Candidacy::create($validated);

        return response()->json([
            'message' => 'Candidacy successfully created',
            'candidacy' => $candidacy
        ], 201);
    }

    // Change candidacy status
    public function changeCandidacyStatus(Request $request, Candidacy $candidacy)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in(Candidacy::$validStatuses)],
        ]);

        // Check if the candidacy belongs to an offer by the current employer
        if ($candidacy->offer->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $candidacy->status = $request->status;
        $candidacy->save();

        return response()->json([
            'message' => 'Candidacy status updated to ' . $request->status,
            'candidacy' => $candidacy,
        ]);
    }

    // Generate PDF for accepted candidacies
    public function generatePDF($offerId)
    {
        $user = Auth::user();
        $employer = Employer::where('user_id', $user->id)->first();
        if (!$employer) {
            return response()->json(['message' => 'Employer not found'], 404);
        }
        $offer = Offer::where('id', $offerId)
                ->where('employer_id', $employer->id)
                ->first();
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }
        $candidacies = Candidacy::where('offer_id', $offer->id)
                                ->where('status', Candidacy::STATUS_ACCEPTED)
                                ->with(['candidate'])
                                ->get();

        $pdf = Pdf::loadView('candidacies.accepted', compact('candidacies', 'offer', 'employer'));
        return $pdf->download('accepted-candidacies.pdf');
    }
}