<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{

    // Fetch all offers or with filters
    public function findAllOffers(Request $request)
    {
        $filters = $request->only(['location', 'type', 'salary_range', 'experience']);
        $perPage = $request->input('perPage', 10);
        $offers = Offer::filterOffers($filters)->paginate($perPage);

        return response()->json($offers);
    }

    // Create a new offer
    public function createOffer(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'type' => ['required', 'string', Rule::in(Offer::$offerType)],
            'salary' => 'required|string',
            'experience' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $offer = new Offer();
        $offer->employer_id = Auth::id();
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->location = $request->location;
        $offer->type = $request->type;
        $offer->salary = $request->salary;
        $offer->experience = $request->experience;
        $offer->deadline = $request->deadline;
        $offer->save();

        return response()->json([
            'message' => 'Offer successfully created'
        ], 201);
    }

    // Get one single offer
    public function findOffer(Offer $offer)
    {
        return response()->json($offer);
    }

    // Update a single offer
    public function updateOffer(Request $request, Offer $offer)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'type' => ['required', 'string', Rule::in(Offer::$offerType)],
            'salary' => 'required|string',
            'experience' => 'required|string',
            'deadline' => 'required|date_format:Y-m-d',
        ]);

        if ($user->id != $offer->employer_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $offer->update($request->all());

        return response()->json(['message' => 'Offer updated successfully', 'offer' => $offer]);
    }

    // Remove a single offer
    public function deleteOffer(Offer $offer)
    {
        if ($offer->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $offer->delete();
        return response()->json([
            'message' => 'Offer successfully deleted',
        ], 200);
    }
}
