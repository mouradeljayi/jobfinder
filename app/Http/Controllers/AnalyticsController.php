<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Candidacy;
use App\Models\Candidate;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function employerOffers()
    {
        $offersByEmployer = Offer::groupBy('employer_id')->selectRaw('count(*) as count, employer_id')->get();
        return response()->json(['offersByEmployer' => $offersByEmployer]);
    }
    public function candidatiesOffer()
    {
        $candidaciesForOffers = Candidacy::groupBy('offer_id')->selectRaw('count(*) as count, offer_id')->get();
        return response()->json(['candidaciesForOffers' => $candidaciesForOffers]);
    }

    public function describeCandidates($offer_id)
    {

        $candidaciesForOffer = Candidacy::where('offer_id', $offer_id)->get();


        $candidateIds = $candidaciesForOffer->pluck('candidate_id')->unique();


        $candidates = Candidate::whereIn('id', $candidateIds)->get();


        $countByEducationLevel = $candidates->groupBy('education_level')->map->count();


        $countByExperience = $candidates->groupBy('experience')->map->count();


        $countByLocation = $candidates->groupBy('location')->map->count();


        return response()->json([
            'candidacies' => $candidaciesForOffer,
            'candidates' => $candidates,
            'count_by_experience' => $countByExperience,
            'count_by_location' => $countByLocation,
            'count_by_education_level' => $countByEducationLevel
        ]);
    }

    public function companyRating($companyId)
    {
    }
}
