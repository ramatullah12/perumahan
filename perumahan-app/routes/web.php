<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\LaporanController;
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
            // Memastikan Customer langsung masuk ke daftar pesanan mereka
            'customer' => redirect()->route('customer.booking.index'), 
            default    => redirect()->route('customer.booking.index'),
        };
    })->name('dashboard');

    // ======================
    // ADMIN SECTION
    // ======================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        // DASHBOARD
        Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard');

        // BOOKING MANAGEMENT
        Route::get('/booking', [BookingController::class, 'indexAdmin'])->name('admin.booking.index');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('admin.booking.show');
        Route::put('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');

        // CRUD PROYEK, TIPE, & UNIT
        Route::resource('project', ProjectController::class)->names('admin.project');
        Route::resource('tipe', TipeController::class)->names('admin.tipe');
        Route::resource('unit', UnitController::class)->names('admin.unit');
        
        // AJAX UNIT & STATUS
        Route::patch('unit/{unit}/status', [UnitController::class, 'updateStatus'])->name('admin.unit.updateStatus');
        Route::get('get-tipe/{projectId}', [UnitController::class, 'getTipeByProject'])->name('admin.unit.getTipe');

        // PROGRES PEMBANGUNAN
        // Resource ini menyediakan index, create, store, show, edit, update, destroy
        Route::resource('progres', ProgresController::class)->names('admin.progres');
        
        // Rute eksplisit tambahan untuk halaman edit jika resource tidak terbaca sempurna
        Route::get('/progres/{id}/edit', [ProgresController::class, 'edit'])->name('admin.progres.edit');

        // LAPORAN
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
        // Halaman awal customer jika diakses manual
        Route::view('/dashboard', 'dashboard.customer')->name('customer.dashboard');
        
        // PEMESANAN UNIT (BOOKING)
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('customer.booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('customer.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('customer.booking.store');
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('customer.booking.getUnits');
        
        // MONITORING PEMBANGUNAN
        // Menyeragamkan rute agar sinkron dengan Controller indexCustomer
        Route::get('/progres', [ProgresController::class, 'indexCustomer'])->name('customer.progres.index');
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