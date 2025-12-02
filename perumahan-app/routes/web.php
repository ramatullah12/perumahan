<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =====================================
// DASHBOARD REDIRECT BERDASARKAN ROLE
// =====================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'owner' => redirect('/owner/dashboard'),
            default => redirect('/customer/dashboard'),
        };
    })->name('dashboard');

    // ======================
    // ADMIN
    // ======================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        });
    });

    // ======================
    // OWNER
    // ======================
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner/dashboard', function () {
            return view('dashboard.owner');
        });
    });

    // ======================
    // CUSTOMER
    // ======================
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer/dashboard', function () {
            return view('dashboard.customer');
        });
    });

    // ======================
    // PROFILE DEFAULT LARAVEL
    // ======================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
