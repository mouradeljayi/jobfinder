<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidacyController;

use Illuminate\Support\Facades\Route;



// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('is_employer')->group(function () {
        Route::get('/candidacies/{offer}', [CandidacyController::class, 'getEmployerCandidacies']);
        Route::put('/candidacies/{candidacy}', [CandidacyController::class, 'changeCandidacyStatus']);
        Route::get('/candidacies/{offer}/pdf', [CandidacyController::class, 'generatePDF'])
        ->name('api.candidacies.generatePDF');

    });

    Route::middleware('is_candidate')->group(function () {
        Route::post('/candidacies', [CandidacyController::class, 'createCandidacy']);
    });

});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);