<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\EmployerReviewController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;

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

    // Common Routes
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
});

// Public Routes

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/reviews', [EmployerReviewController::class, 'getAllReviews']);

//google authentication
Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

//github authentication