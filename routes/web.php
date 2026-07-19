<?php

use Illuminate\Support\Facades\Route;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivityMonitorController;

// Import Controller Penyuluh
use App\Http\Controllers\Penyuluh\DashboardController as PenyuluhDashboardController;
use App\Http\Controllers\Penyuluh\ActivityController;

// Halaman utama (Arahkan langsung ke halaman login jika belum login)
Route::get('/', function () {
    return redirect()->route('login');
});

// Group route yang membutuhkan Auth (Harus Login)
Route::middleware('auth')->group(function () {

    // ==========================================
    // ROUTES UNTUK ADMIN
    // ==========================================
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // Halaman: /admin/dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            
            // CRUD Master Data
            Route::resource('users', UserController::class);
            Route::resource('categories', CategoryController::class);
            
            // Monitor Kegiatan Semua Penyuluh (Read-only & Delete)
            Route::get('activities', [ActivityMonitorController::class, 'index'])->name('activities.index');
            Route::get('activities/{activity}', [ActivityMonitorController::class, 'show'])->name('activities.show');
            Route::delete('activities/{activity}', [ActivityMonitorController::class, 'destroy'])->name('activities.destroy');
    });

    // ==========================================
    // ROUTES UNTUK PENYULUH
    // ==========================================
    Route::middleware('role:penyuluh')
        ->prefix('penyuluh')
        ->name('penyuluh.')
        ->group(function () {
            
            // Halaman: /penyuluh/dashboard
            Route::get('/dashboard', [PenyuluhDashboardController::class, 'index'])->name('dashboard');
            
            // CRUD Kegiatan Sendiri (otomatis membuat route index, create, store, show, edit, update, destroy)
            Route::resource('activities', ActivityController::class);
            
            // Route khusus untuk Generate Laporan (PDF / Word)
            Route::get('activities/{activity}/report', [ActivityController::class, 'generateReport'])->name('activities.report');
    });

});

// Auth Routes bawaan Laravel Breeze (Login, Lupa Password, Reset Password)
require __DIR__.'/auth.php';
