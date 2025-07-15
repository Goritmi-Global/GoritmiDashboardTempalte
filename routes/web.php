<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GlobalPageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [GlobalPageController::class, 'dashboard'])->name('dashboard');
    Route::get('/accounting', [GlobalPageController::class, 'accounting'])->name('accounting');
    Route::get('/crm', [GlobalPageController::class, 'crm'])->name('crm');
    Route::get('/hr', [GlobalPageController::class, 'hr'])->name('hr');
    Route::get('/projects', [GlobalPageController::class, 'projects'])->name('projects'); 
    Route::get('/projects/new', [GlobalPageController::class, 'projects'])->name('projects.new'); 
    Route::get('/projects/existing', [GlobalPageController::class, 'projects'])->name('projects.existing'); 
    Route::get('/projects/dealing', [GlobalPageController::class, 'projects'])->name('projects.dealing'); 

     
});

require __DIR__.'/auth.php';
