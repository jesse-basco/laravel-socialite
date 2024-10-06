<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::post('register', [UserController::class, 'store']);
Route::post('sign-in', [UserController::class, 'sign_in']);
Route::get('auth/facebook/redirect', [UserController::class, 'facebook_sign_in_redirect']);
Route::get('auth/facebook/callback', [UserController::class, 'handle_facebook_callback']);

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('facebook')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('facebook.com')->user();
//     $user->token;
// });
