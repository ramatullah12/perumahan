<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
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
            'admin'  => redirect('/admin/dashboard'),
            'owner'  => redirect('/owner/dashboard'),
            default  => redirect('/customer/dashboard'),
        };
    })->name('dashboard');

    // ======================
    // ADMIN SECTION
    // ======================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        // DASHBOARD
        Route::view('/dashboard', 'dashboard.admin');

        // BOOKING MANAGEMENT
        Route::get('/booking', [BookingController::class, 'indexAdmin'])->name('admin.booking.index');
        Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('admin.booking.show');
        Route::put('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');
    });

    // ======================
    // OWNER SECTION
    // ======================
    Route::middleware(['role:owner'])->prefix('owner')->group(function () {
        Route::view('/dashboard', 'dashboard.owner');
    });

    // ======================
    // CUSTOMER SECTION
    // ======================
    Route::middleware(['role:customer'])->prefix('customer')->group(function () {

        // DASHBOARD
        Route::view('/dashboard', 'dashboard.customer');

        // BOOKING COSTUMER
        Route::get('/booking', [BookingController::class, 'indexCustomer'])->name('customer.booking.index');
        Route::get('/booking/create', [BookingController::class, 'create'])->name('customer.booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('customer.booking.store');
    });

    // ======================
    // PROFILE DEFAULT LARAVEL
    // ======================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
