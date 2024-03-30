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
    
        // If the review is a reply, update it directly
        if ($review->parent_id !== null) {
            $review->update($request->only(['feedback', 'rating']));
    
            return response()->json([
                'message' => 'Reply updated successfully',
                'review' => $review
            ]);
        }
    
        // If the review is a parent review, check if it has any replies
        if ($review->replies()->exists()) {
            return response()->json(['message' => 'Cannot update parent review with existing replies'], 422);
        }
    
        // If the review is a parent review without any replies, update it
        $review->update($request->only(['feedback', 'rating']));
    
        return response()->json([
            'message' => 'Parent review updated successfully',
            'review' => $review
        ]);
    }
    

    // Delete review
    public function deleteReview(EmployerReview $review)
    {
        if ($review->candidate_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->replies()->delete();
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }

    // Fetch review of a single employer
    public function getAllReviews($employer_id)
    {
        $reviews = EmployerReview::with('candidate', 'replies', 'replies.candidate')
            ->where('employer_id', $employer_id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    // Reply to a review
    public function replyToReview(Request $request, $reviewId)
    {
        $request->validate([
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $parentReview = EmployerReview::findOrFail($reviewId);

        $reply = EmployerReview::create([
            'employer_id' => $parentReview->employer_id,
            'candidate_id' => Auth::id(),
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'parent_id' => $reviewId
        ]);

        return response()->json([
            'message' => 'Reply added successfully.',
            'reply' => $reply
        ], 201);
    }
}
