<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

// Public profile page
Route::get('/{username}', [PublicProfileController::class, 'show'])->name('public.profile');

// Redirect link & track click
Route::get('/go/{id}', [LinkController::class, 'redirect'])->name('link.redirect');

// Dashboard
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Link management
    Route::resource('links', LinkController::class)->except(['show']);
    
    // Analytics
    Route::get('/analytics', [LinkController::class, 'analytics'])->name('analytics');
});

require __DIR__.'/auth.php';
