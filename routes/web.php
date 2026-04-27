<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterTutorialController;
use App\Http\Controllers\DetailTutorialController;
use App\Http\Controllers\PublicController;

// =============================================
// ROUTE PUBLIK (tanpa login)
// =============================================

// --- Halaman Login ---
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login.form');
Route::post('/login', [AuthController::class, 'process'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Halaman Publik: Presentation & PDF ---
Route::get('/tutorial/{url}', [PublicController::class, 'presentation'])->name('tutorial.show');
Route::get('/tutorial-data/{url}', [PublicController::class, 'getLatestDetails'])->name('tutorial.data');
Route::get('/tutorial-pdf/{url}', [PublicController::class, 'pdf'])->name('tutorial.pdf');

// =============================================
// ROUTE TERPROTEKSI (harus login)
// =============================================
Route::middleware('auth.custom')->group(function () {
    // --- CRUD Master Tutorial ---
    Route::resource('master-tutorial', MasterTutorialController::class);

    // --- CRUD Detail Tutorial ---
    Route::get('detail-tutorial/by-master/{masterId}', [DetailTutorialController::class, 'byMaster'])
        ->name('detail-tutorial.byMaster');
    Route::resource('detail-tutorial', DetailTutorialController::class);
});
