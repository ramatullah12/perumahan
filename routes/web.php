<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    BookingController, ProfileController, ProjectController,
    TipeController, UnitController, ProgresController,
    LaporanController, NotificationController, DashboardController, 
    UserController, LandingController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================================
// PUBLIC ACCESS (GUEST & ALL ROLES)
// =====================================
Route::get('/', [LandingController::class, 'index'])->name('welcome');
Route::get('/proyek/detail/{id}', [LandingController::class, 'show'])->name('proyek.detail');

Route::middleware(['auth'])->group(function () {

    // REDIRECT DASHBOARD BERDASARKAN ROLE
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
        
        // --- Unit Management ---
        // Rute AJAX untuk mengambil tipe berdasarkan proyek (Mencegah "Gagal memuat tipe")
        Route::get('/get-tipe/{projectId}', [UnitController::class, 'getTipeByProject'])->name('unit.getTipe');
        
        // Rute patch untuk update status cepat di index
        Route::patch('unit/{unit}/status', [UnitController::class, 'updateStatus'])->name('unit.updateStatus');
        
        // Resource Unit
        Route::resource('unit', UnitController::class);
        
        // --- Resources Lainnya ---
        Route::resource('project', ProjectController::class);
        Route::resource('tipe', TipeController::class);
        Route::resource('progres', ProgresController::class);
        
        // --- Booking Management (Admin) ---
        Route::prefix('booking')->as('booking.')->group(function () {
            Route::get('/', [BookingController::class, 'indexAdmin'])->name('index');
            Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
            Route::put('/{booking}/status', [BookingController::class, 'updateStatus'])->name('updateStatus');
        });
        
        // --- Laporan ---
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export');
    });

    // =====================================
    // OWNER SECTION
    // =====================================
    Route::middleware(['role:owner'])->prefix('owner')->as('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');
        Route::get('/analisis', [LaporanController::class, 'analisisOwner'])->name('analisis.index');
        Route::get('/progres', [ProgresController::class, 'indexOwner'])->name('progres.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    });

    // =====================================
    // CUSTOMER SECTION
    // =====================================
    Route::middleware(['role:customer'])->prefix('customer')->as('customer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customer'])->name('dashboard');
        Route::get('/proyek', [UnitController::class, 'jelajahiProyek'])->name('proyek.index');
        
        // --- TAMBAHAN: Rute AJAX untuk mengambil unit berdasarkan proyek ---
        Route::get('/get-units/{projectId}', [BookingController::class, 'getUnitsByProject'])->name('getUnits');
        
        // --- Booking Customer ---
        Route::resource('booking', BookingController::class)->only(['index', 'create', 'store']);
        
        // --- Progres & Notifikasi ---
        Route::get('/progres', [ProgresController::class, 'indexCustomer'])->name('progres.index');
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    });

    // =====================================
    // PROFILE SETTINGS (SHARED)
    // =====================================
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';