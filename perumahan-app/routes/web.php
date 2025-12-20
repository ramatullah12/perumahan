<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\LaporanController;
// TAMBAHKAN IMPORT INI
use App\Http\Controllers\NotificationController; 
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
            'customer' => redirect()->route('customer.booking.index'), 
            default    => redirect()->route('customer.booking.index'),
        };
    })->name('dashboard');

    // ======================
    // ADMIN SECTION
    // ======================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard');
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
    });

    // ======================
    // OWNER SECTION
    // ======================
    Route::middleware(['role:owner'])->prefix('owner')->group(function () {
        Route::view('/dashboard', 'dashboard.owner')->name('owner.dashboard');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('owner.laporan.index');
    });

    // ======================
    // CUSTOMER SECTION
    // ======================
    Route::middleware(['role:customer'])->prefix('customer')->group(function () {
        Route::view('/dashboard', 'dashboard.customer')->name('customer.dashboard');
        
        // JELAJAHI PROYEK
        Route::get('/proyek', [UnitController::class, 'jelajahiProyek'])->name('customer.proyek.index');
        
        // PEMESANAN UNIT (BOOKING)
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('customer.booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('customer.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('customer.booking.store');
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('customer.booking.getUnits');
        
        // MONITORING PEMBANGUNAN
        Route::get('/progres', [ProgresController::class, 'indexCustomer'])->name('customer.progres.index');

        // =====================================
        // TAMBAHKAN RUTE NOTIFIKASI DI SINI
        // =====================================
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    });

    // ======================
    // PROFILE SETTINGS
    // ======================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';