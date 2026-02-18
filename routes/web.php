<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', [PublicController::class, 'index']);
Route::post('/contact', [PublicController::class, 'submitContactForm']);
Route::get('/download-profile/{year}', [PublicController::class, 'downloadPdf']);

// Hidden Admin Logic
Route::get('/portal-access-secret', [AuthController::class, 'showLogin'])->name('login');
Route::post('/portal-access-secret', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/grid', [AdminController::class, 'gridView']);
    Route::get('/export', [AdminController::class, 'export']);
    Route::post('/content', [AdminController::class, 'store']);
    Route::patch('/content/{content}', [AdminController::class, 'update']);
    Route::delete('/content/{content}', [AdminController::class, 'destroy']);
    Route::delete('/year/{year}', [AdminController::class, 'destroyYear']);
    Route::delete('/inquiry/{inquiry}', [AdminController::class, 'destroyInquiry']);
    Route::post('/year/duplicate', [AdminController::class, 'duplicateYear']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
});
