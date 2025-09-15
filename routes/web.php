<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use Inertia\Inertia;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rutas para registro de estudiante
    Route::prefix('registration')->name('registration.')->group(function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
        Route::post('/academic-info', [RegistrationController::class, 'academic_information'])->name('academic-info');
        Route::get('/academic-interests', [RegistrationController::class, 'academic_interest'])->name('academic-interests');
        Route::put('/academic-interests', [RegistrationController::class, 'interests_info'])->name('interests-info');
    });
});
