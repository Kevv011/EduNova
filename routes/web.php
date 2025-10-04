<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use Inertia\Inertia;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\InstructorRequestController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;

Route::get('/', [LandingController::class, 'index'])->name('home');

// Solicitud de instructores
Route::resource('instructor-requests', InstructorRequestController::class);

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

    // Ruta para los cursos
    Route::resource('courses', CourseController::class);
    Route::prefix('courses/{course}')->name('courses.')->group(function () {
        Route::get('/students', [CourseController::class, 'students'])->name('students');
        Route::get('/modules', [CourseController::class, 'modules'])->name('modules');
    });

    // Ruta para modulos
    Route::resource('modules', ModuleController::class);
    Route::prefix('/modules/{module}')->name('modules.')->group(function () {
        Route::put('/disable', [ModuleController::class, 'disable'])->name('disable');
        Route::put('/enable', [ModuleController::class, 'enable'])->name('enable');
        Route::get('lessons', [ModuleController::class, 'lessons'])->name('lessons');
    });

    // Ruta para Lessons
    Route::resource('lessons', LessonController::class);
    Route::post('/lessons/content/{lesson}', [LessonController::class, 'updateContent'])->name('lessons.content.update');
});
