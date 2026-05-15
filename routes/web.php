<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VerifikasiBookingController;
use App\Http\Controllers\Admin\ResetBookingController;
use App\Http\Controllers\ProfileController;

// Halaman utama redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman dashboard default Laravel (untuk guru, dikerjakan Cia)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup route khusus admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Verifikasi booking
        Route::get('/verifikasi', [VerifikasiBookingController::class, 'index'])
            ->name('verifikasi.index');
        Route::patch('/verifikasi/{booking}/setujui', [VerifikasiBookingController::class, 'setujui'])
            ->name('verifikasi.setujui');
        Route::patch('/verifikasi/{booking}/tolak', [VerifikasiBookingController::class, 'tolak'])
            ->name('verifikasi.tolak');

        // Reset booking
        Route::get('/reset', [ResetBookingController::class, 'index'])
            ->name('reset.index');
        Route::patch('/reset/{booking}', [ResetBookingController::class, 'reset'])
            ->name('reset.booking');

        // Calendar
        Route::get('/calendar', function () {
            return view('admin.calendar');
        })->name('calendar');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
