<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function findAllOffers()
    {
        $offers = Offer::latest()->get();
        return response()->json([
            'message' => 'Offers successfully retrieved',
            'offers' => $offers
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function createOffer(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'location' => 'required|string',
                'type' => ['required', 'string', Rule::in(Offer::$offerType)],
                'salary' => 'required|string',
                'experience' => 'required|string',
                'deadline' => 'required|date_format:Y-m-d',
            ]);

            // Attribuer employer_id avant la création de l'offre
            $validated['employer_id'] = $user->id;

            $offer = Offer::create($validated);

            return response()->json([
                'message' => 'Offer successfully created',
                'offer' => $offer
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }




    /**
     * Display the specified resource.
     */
    public function findOffer(string $id)
    {
        $singleOfferFromDB = Offer::where('id', $id)->first();

        if(!is_null($singleOfferFromDB)){
            return response()->json([
                'message' => 'Offer found',
                'offer' => $singleOfferFromDB
            ], 200);
        } else {
            return response()->json([
                'message' => 'Offer not found',
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateOffer(Request $request, string $idOffer)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'type' => ['required', 'string', Rule::in(Offer::$offerType)],
            'salary' => 'required|string',
            'experience' => 'required|string',
            'deadline' => 'required|date_format:Y-m-d',
        ]);

        $singleOfferFromDB = Offer::find($idOffer);

        if ($user->id != $singleOfferFromDB->employer_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $singleOfferFromDB->update($validatedData);

        return response()->json(['message' => 'Offer updated successfully', 'offer' => $singleOfferFromDB]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function deleteOffer(Offer $offer)
    {
        if ($offer->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $offer->delete();
        return response()->json([
            'message' => 'Offer successfully deleted',
        ], 201);

    }
}
