<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {

    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

});

Route::middleware('auth')->group(function () {

    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

});

/* LOGOUT */

Route::post('/logout', function (Request $request) {

    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');

})->name('logout');