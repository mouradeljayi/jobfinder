<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\EmployerReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;



// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    // Employers Routes
    Route::middleware('is_employer')->group(function () {
        Route::get('/candidacies/{offer}', [CandidacyController::class, 'getEmployerCandidacies']);
        Route::put('/candidacies/{candidacy}', [CandidacyController::class, 'changeCandidacyStatus']);
        Route::get('/candidacies/{offer}/pdf', [CandidacyController::class, 'generatePDF'])
        ->name('api.candidacies.generatePDF');

    });

    // Candidates Routes
    Route::middleware('is_candidate')->group(function () {
        Route::post('/candidacies', [CandidacyController::class, 'createCandidacy']);
        Route::post('/reviews', [EmployerReviewController::class, 'createReview']);
        Route::put('/reviews/{review}', [EmployerReviewController::class, 'updateReview']);
        Route::delete('/reviews/{review}', [EmployerReviewController::class, 'deleteReview']);
    });

    //Offers Routes
    Route::middleware('is_employer')->group(function () {
        Route::get('/offers', [OfferController::class, 'findAllOffers']);
        Route::get('/offers/{offer}', [OfferController::class, 'findOffer']);
        Route::post('/offers/{offer}', [OfferController::class, 'createOffer']);
        Route::put('/offers/{offer}', [OfferController::class, 'updateOffer']);
        Route::delete('/offers/{offer}', [OfferController::class, 'deleteOffer']);
    });



    // Common Routes
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);

});

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/reviews', [EmployerReviewController::class, 'getAllReviews']);