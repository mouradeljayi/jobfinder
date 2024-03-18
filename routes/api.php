<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('is_employer')->group(function () {
        Route::get('/candidacies/{offer}', [CandidacyController::class, 'employerCandidacies']);
        Route::put('/candidacies/{candidacy}', [CandidacyController::class, 'changeCandidacyStatus']);
        //offer routes
        Route::get('/offers', [OfferController::class,'index'])->name('offers.index');
        Route::get('/offers/create', [OfferController::class,'create'])->name('offers.create');
        Route::post('/offers/create', [OfferController::class,'store'])->name('offers.store');
        Route::get('/offers/{offer}', [OfferController::class,'show'])->name('offers.show');
        Route::get('/offers/{offer}/edit', [OfferController::class,'edit'])->name('offers.edit');
        Route::put('/offers/{offer}',[OfferController::class,'update'])->name('offers.update');
        Route::delete('/offers/{offer}',[OfferController::class,'destroy'])->name('offers.destroy');

    });

Route::middleware('is_candidate')->group(function () {
        Route::post('/candidacies', [CandidacyController::class, 'createCandidacy']);
    });


});