<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KursusController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdminController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Kursus routes
    Route::get('/kursus', [KursusController::class, 'index']);
    Route::get('/kursus/{id}', [KursusController::class, 'show']);
    
    // Profile routes
    Route::post('/profile', [ProfileController::class, 'update']);
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats']);
        Route::get('/recent-enrollments', [AdminController::class, 'getRecentEnrollments']);
    });
});
