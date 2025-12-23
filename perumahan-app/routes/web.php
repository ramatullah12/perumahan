<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =====================================
// REDIRECT DASHBOARD BERDASARKAN ROLE
// =====================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'owner'    => redirect()->route('owner.dashboard'),
            'customer' => redirect()->route('customer.dashboard'), // Diarahkan ke dashboard khusus
            default    => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    // =====================================
    // ADMIN SECTION
    // =====================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Path view: resources/views/dashboard/admin/index.blade.php
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        
        Route::get('/booking', [BookingController::class, 'indexAdmin'])->name('admin.booking.index');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('admin.booking.show');
        Route::put('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');
        
        Route::resource('project', ProjectController::class)->names('admin.project');
        Route::resource('tipe', TipeController::class)->names('admin.tipe');
        Route::resource('unit', UnitController::class)->names('admin.unit');
        Route::patch('unit/{unit}/status', [UnitController::class, 'updateStatus'])->name('admin.unit.updateStatus');
        Route::get('get-tipe/{projectId}', [UnitController::class, 'getTipeByProject'])->name('admin.unit.getTipe');
        
        Route::resource('progres', ProgresController::class)->names('admin.progres');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('admin.laporan.export');
    });

    // =====================================
    // OWNER SECTION
    // =====================================
    Route::middleware(['role:owner'])->prefix('owner')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('owner.laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('owner.laporan.export');
    });

    // =====================================
    // CUSTOMER SECTION
    // =====================================
    Route::middleware(['role:customer'])->prefix('customer')->group(function () {
        // PERBAIKAN: Gunakan Controller, jangan Route::view agar data notifikasi terkirim
        // Menuju file: resources/views/dashboard/customer/index.blade.php
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('customer.dashboard');
        
        Route::get('/proyek', [UnitController::class, 'jelajahiProyek'])->name('customer.proyek.index');
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('customer.booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('customer.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('customer.booking.store');
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('customer.booking.getUnits');
        Route::get('/progres', [ProgresController::class, 'indexCustomer'])->name('customer.progres.index');
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    });

    // =====================================
    // PROFILE SETTINGS
    // =====================================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';