<?php

use App\Http\Controllers\GithubAuthController;
use Illuminate\Support\Facades\Route;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return 'Weclome to JobFinder API';
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

//
Route::get('/auth/github', [GithubAuthController::class, 'redirect'])->name('github-auth');
Route::get('/auth/github/callback', [GithubAuthController::class, 'callbackGithub']);
