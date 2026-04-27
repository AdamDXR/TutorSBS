<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// --- REST API Internal: Daftar tutorial berdasarkan kode mata kuliah ---
Route::get('/tutorials', [ApiController::class, 'index']);
