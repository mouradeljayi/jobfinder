<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Offer;
use App\Models\Cv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {

        $candidate  = Auth::user();
        if (!$candidate) {
            return response()->json(['message' => 'No authenticated candidate found'], 404);
        } else {
        }
        $cv = Cv::where('candidate_id', $candidate->id)->first();
        if ($cv) {
            $candidateSkills = $cv->skills;  // Assumons que c'est un tableau
            $offers = Offer::where(function ($query) use ($candidateSkills) {
                foreach ($candidateSkills as $skill) {
                    $query->orWhere(DB::raw('UPPER(skills)'), 'LIKE', '%' . strtoupper($skill) . '%');
                }
            })
                ->orWhere('location', strtoupper($candidate->location))
                ->get();
        }


        return response()->json([
            // 'candidate' => $candidate,
            // 'CV' => $cv,
            'data' => $offers
        ]);
    }
}
