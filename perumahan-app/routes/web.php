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
        'admin'  => redirect()->route('admin.dashboard'),
        'owner'  => redirect()->route('owner.dashboard'),
        'customer' => redirect()->route('customer.booking.index'), 
        default  => redirect()->route('customer.booking.index'),
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
        
        /**
         * PERBAIKAN: Menggunakan PUT untuk update status agar sesuai dengan form di admin
         */
        Route::put('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');

        // CRUD MANAGEMENT
        Route::resource('project', ProjectController::class)->names('admin.project');
        Route::resource('tipe', TipeController::class)->names('admin.tipe');
        Route::resource('unit', UnitController::class)->names('admin.unit');
        
        // UNIT STATUS & AJAX (DASHBOARD ADMIN)
        Route::patch('unit/{unit}/status', [UnitController::class, 'updateStatus'])->name('admin.unit.updateStatus');
        Route::get('get-tipe/{projectId}', [UnitController::class, 'getTipeByProject'])->name('admin.unit.getTipe');

        // PROGRES PEMBANGUNAN
        Route::resource('progres', ProgresController::class)->names('admin.progres');

        // LAPORAN & ANALYTICS
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
        
        // BOOKING SYSTEM
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('customer.booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('customer.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('customer.booking.store');

        /**
         * RUTE BARU: AJAX untuk mengambil Unit berdasarkan Proyek yang dipilih
         * Digunakan untuk memisahkan dropdown Proyek dan Unit di halaman Booking
         */
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('customer.booking.getUnits');
        
        // MONITORING UNIT & PROGRESS
        Route::get('/my-progres', [ProgresController::class, 'indexCustomer'])->name('customer.progres.index');

    });

    // ======================
    // PROFILE DEFAULT
    // ======================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';