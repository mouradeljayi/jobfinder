<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidacyController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\EmployerReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/reviews', [EmployerReviewController::class, 'getAllReviews']);
Route::get('/offers', [OfferController::class, 'findAllOffers']);
Route::get('/offers/{offer}', [OfferController::class, 'findOffer']);

// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    // Employers Routes
    Route::middleware('is_employer')->group(function () {
        Route::get('/candidacies/{offer}', [CandidacyController::class, 'getEmployerCandidacies']);
        Route::put('/candidacies/{candidacy}', [CandidacyController::class, 'changeCandidacyStatus']);
        Route::get('/candidacies/{offer}/pdf', [CandidacyController::class, 'generatePDF'])
        ->name('api.candidacies.generatePDF');

        //Offers Routes
        Route::post('/offers/{offer}', [OfferController::class, 'createOffer']);
        Route::put('/offers/{offer}', [OfferController::class, 'updateOffer']);
        Route::delete('/offers/{offer}', [OfferController::class, 'deleteOffer']);

    });

    // Candidates Routes
    Route::middleware('is_candidate')->group(function () {
        Route::post('/candidacies', [CandidacyController::class, 'createCandidacy']);
        Route::post('/reviews', [EmployerReviewController::class, 'createReview']);
        Route::put('/reviews/{review}', [EmployerReviewController::class, 'updateReview']);
        Route::delete('/reviews/{review}', [EmployerReviewController::class, 'deleteReview']);

        //CV Routes
         Route::get('/cv', [CvController::class, 'getCvByCandidate']);
         Route::post('/cv/{cv}', [CvController::class, 'createCv']);
         Route::delete('/cv/{cv}', [CvController::class, 'deleteCv']);

         Route::post('/cv/{cv_path}', [CvController::class, 'uploadCv']);
         Route::put('/cv/{cv_path}', [CvController::class, 'updateCvPath']);
         Route::delete('/cv/{cv_path}', [CvController::class, 'deleteCvPath']);

         Route::get('/cv/education', [CvController::class, 'getEducations']);
         Route::post('/cv/education/{education}', [CvController::class, 'createEducation']);
         Route::put('/cv/education/{education}', [CvController::class, 'updateEducation']);
         Route::delete('/cv/education/{education}', [CvController::class, 'deleteEducation']);

         Route::get('/cv/skills', [CvController::class, 'getSkills']);
         Route::post('/cv/skills/{skill}', [CvController::class, 'createSkill']);
         Route::put('/cv/skills/{skill}', [CvController::class, 'updateSkill']);
         Route::delete('/cv/skills/{skill}', [CvController::class, 'deleteSkill']);

         Route::get('/cv/certifications', [CvController::class, 'getCertifications']);
         Route::post('/cv/certifications/{certification}', [CvController::class, 'createCertification']);
         Route::put('/cv/certifications/{certification}', [CvController::class, 'updateCertification']);
         Route::delete('/cv/certifications/{certification}', [CvController::class, 'deleteCertification']);

         Route::get('/cv/languages', [CvController::class, 'getLanguages']);
         Route::post('/cv/languages/{language}', [CvController::class, 'createLanguage']);
         Route::put('/cv/languages/{language}', [CvController::class, 'updateLanguage']);
         Route::delete('/cv/languages/{language}', [CvController::class, 'deleteLanguage']);

         Route::get('/cv/experiences', [CvController::class, 'getExperiences']);
         Route::post('/cv/experiences/{experience}', [CvController::class, 'createExperience']);
         Route::put('/cv/experiences/{experience}', [CvController::class, 'updateExperience']);
         Route::delete('/cv/experiences/{experience}', [CvController::class, 'deleteExperience']);

     });

    // Common Routes
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);

});
