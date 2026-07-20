<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Penyuluh\DashboardController as PenyuluhDashboard;
use App\Http\Controllers\Penyuluh\ActivityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivityMonitorController;
use Illuminate\Support\Facades\Route;

// Halaman Awal
Route::get('/', function () {
    return redirect()->route('login'); // Langsung kita arahkan ke halaman login
});

// Otomatisasi Redirect setelah Login (Opsional tapi berguna)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('penyuluh.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Edit Profil (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTE KHUSUS PENYULUH
// ==========================================
Route::middleware(['auth', 'role:penyuluh'])->prefix('penyuluh')->name('penyuluh.')->group(function () {
    Route::get('/dashboard', [PenyuluhDashboard::class, 'index'])->name('dashboard');
    
    // Rute untuk Manajemen Kegiatan Penyuluh
    Route::resource('activities', ActivityController::class);
});

// ==========================================
// RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Rute untuk Manajemen Data oleh Admin
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('monitor', ActivityMonitorController::class);
});

// Auth bawaan Laravel
require __DIR__.'/auth.php';