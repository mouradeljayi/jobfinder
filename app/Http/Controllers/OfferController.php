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
    public function index()
    {
        $offers = Offer::latest()->get();
        return response()->json([
            'message' => 'Offers successfully retrieved',
            'offers' => $offers
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = Auth::all();

        return response()->json([
            'message' => 'Add Form ',

        ], 201);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si un utilisateur est authentifié
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
                $offer = Offer::create($validated);
                $offer->employer_id = $user->id;

            return response()->json([
                'message' => 'offer successfully created'
            ], 200);

        } else {

            return response()->json([
                'message' => 'error'
            ], 401);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = Auth::all();

        return response()->json([
            'message' => 'Edit Form',

        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idOffer)
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
    public function destroy(string $id)
    {
        Offer::where('id',$id)->delete();
        return response()->json([
            'message' => 'Offer successfully deleted',
        ], 201);

    }
}
