<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployerReview;
use Illuminate\Support\Facades\Auth;

class EmployerReviewController extends Controller
{

    // Create review
    public function createReview(Request $request)
    {
        $request->validate([
            'employer_id' => 'required|exists:employers,id',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $candidateId = Auth::user();
        $review = EmployerReview::create([
            'employer_id' => $request->employer_id,
            'candidate_id' => $candidateId->id,
            'feedback' => $request->feedback,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => 'Review successfully submitted.',
            'review' => $review
        ], 201);
    }

    // Update review
    public function updateReview(Request $request, $reviewId)
    {
        $request->validate([
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = EmployerReview::findOrFail($reviewId);

        if ($review->candidate_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update($request->only(['feedback', 'rating']));

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review
        ]);
    }

    // Delete review
    public function deleteReview(EmployerReview $review)
    {
        if ($review->candidate_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }

    // Fetch review of a single employer
    public function getAllReviews($employer_id)
    {
        $reviews = EmployerReview::where('employer_id', $employer_id)
            ->with('candidate')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }
}
