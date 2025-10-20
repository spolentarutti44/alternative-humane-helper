<?php

use App\Http\Controllers\Admin\AnimalController as AdminAnimalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FosterController as AdminFosterController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Animals
    Route::get('/animals', [AdminAnimalController::class, 'index'])->name('admin.animals.index');
    Route::get('/animals/{id}', [AdminAnimalController::class, 'show'])->name('admin.animals.show');
    
    // Fosters
    Route::get('/fosters', [AdminFosterController::class, 'index'])->name('admin.fosters.index');
    Route::get('/fosters/{id}', [AdminFosterController::class, 'show'])->name('admin.fosters.show');
    
    // Schedules
    Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
    
    // Donations
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('admin.donations.index');
    
    // Events
    Route::get('/events', [AdminEventController::class, 'index'])->name('admin.events.index');
});

