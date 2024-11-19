<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }

    return Inertia::render('Auth/Login');
})->name('auth');
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
});

Route::get('/home', function () {
    return Inertia::render('Home');
});
Route::prefix('user')->group(function () {
    Route::post('/login', [AuthController::class, 'store'])->name('login');
    Route::post('/register', [AuthController::class, 'index'])->name('register');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
