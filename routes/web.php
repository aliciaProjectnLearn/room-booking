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

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/verifikasi', [VerifikasiBookingController::class, 'index'])
            ->name('verifikasi.index');
        Route::patch('/verifikasi/{booking}/setujui', [VerifikasiBookingController::class, 'setujui'])
            ->name('verifikasi.setujui');
        Route::patch('/verifikasi/{booking}/tolak', [VerifikasiBookingController::class, 'tolak'])
            ->name('verifikasi.tolak');

        Route::get('/reset', [ResetBookingController::class, 'index'])
            ->name('reset.index');
        Route::patch('/reset/{booking}', [ResetBookingController::class, 'reset'])
            ->name('reset.booking');

        Route::get('/calendar', function () {
            return view('admin.calendar');
        })->name('calendar');

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class)->except(['show', 'destroy']);
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('rooms', \App\Http\Controllers\Admin\RoomManagementController::class)->except(['show', 'destroy']);
        Route::patch('/rooms/{room}/toggle-status', [\App\Http\Controllers\Admin\RoomManagementController::class, 'toggleStatus'])->name('rooms.toggle-status');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
