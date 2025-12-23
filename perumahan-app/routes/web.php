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
            'customer' => redirect()->route('customer.dashboard'),
            default    => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    // =====================================
    // ADMIN SECTION
    // =====================================
    Route::middleware(['role:admin'])->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        Route::get('/booking', [BookingController::class, 'indexAdmin'])->name('booking.index');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::put('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.updateStatus');
        
        Route::resource('project', ProjectController::class);
        Route::resource('tipe', TipeController::class);
        Route::resource('unit', UnitController::class);
        Route::patch('unit/{unit}/status', [UnitController::class, 'updateStatus'])->name('unit.updateStatus');
        Route::get('get-tipe/{projectId}', [UnitController::class, 'getTipeByProject'])->name('unit.getTipe');
        
        Route::resource('progres', ProgresController::class);
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export');
    });

    // =====================================
    // OWNER SECTION
    // =====================================
    Route::middleware(['role:owner'])->prefix('owner')->as('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');
        
        // Memastikan rute progres terdaftar untuk owner
        Route::get('/progres', [ProgresController::class, 'indexOwner'])->name('progres.index');
        
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export');
    });

    // =====================================
    // CUSTOMER SECTION
    // =====================================
    Route::middleware(['role:customer'])->prefix('customer')->as('customer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
        
        Route::get('/proyek', [UnitController::class, 'jelajahiProyek'])->name('proyek.index');
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('booking.getUnits');
        Route::get('/progres', [ProgresController::class, 'indexCustomer'])->name('progres.index');
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