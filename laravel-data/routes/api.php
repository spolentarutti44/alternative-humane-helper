<?php

use App\Http\Controllers\API\AnimalController;
use App\Http\Controllers\API\DonationController;
use App\Http\Controllers\API\DonorController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FosterController;
use App\Http\Controllers\API\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Animals API
Route::apiResource('animals', AnimalController::class);

// Fosters API
Route::apiResource('fosters', FosterController::class);
Route::get('fosters/{id}/assignments', [FosterController::class, 'assignments']);
Route::post('fosters/{id}/assignments', [FosterController::class, 'assignAnimal']);

// Schedules API
Route::apiResource('schedules', ScheduleController::class);

// Donors API
Route::apiResource('donors', DonorController::class);

// Donations API
Route::apiResource('donations', DonationController::class);
Route::post('donations/{id}/receipt', [DonationController::class, 'sendReceipt']);

// Events API
Route::apiResource('events', EventController::class);
Route::get('events/{id}/registrations', [EventController::class, 'registrations']);
Route::post('events/{id}/register', [EventController::class, 'register']);
Route::put('registrations/{id}', [EventController::class, 'updateRegistration']);

