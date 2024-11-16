<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }

    return Inertia::render('Auth/Login');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
