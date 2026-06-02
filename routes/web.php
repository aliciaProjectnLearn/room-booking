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
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->isGuru()) {
        return redirect()->route('guru.dashboard');
    }
    abort(403);
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

        Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('calendar');
        Route::get('/calendar/events', [\App\Http\Controllers\Admin\CalendarController::class, 'getEvents'])->name('calendar.events');
        Route::post('/calendar/booking', [\App\Http\Controllers\Admin\CalendarController::class, 'store'])->name('calendar.store');

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class)->except(['show', 'destroy']);
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('rooms', \App\Http\Controllers\Admin\RoomManagementController::class)->except(['show', 'destroy']);
        Route::patch('/rooms/{room}/toggle-status', [\App\Http\Controllers\Admin\RoomManagementController::class, 'toggleStatus'])->name('rooms.toggle-status');
    });

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'guru'])
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Dummy routes for the sidebar menus for now
        Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal');
        Route::get('/jadwal/events', [\App\Http\Controllers\Guru\JadwalController::class, 'getEvents'])->name('jadwal.events');
        Route::post('/jadwal/booking', [\App\Http\Controllers\Guru\JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/booking/riwayat', function () { return "Riwayat Booking"; })->name('booking.riwayat');
        Route::get('/booking/buat', function () { return "Buat Booking Baru"; })->name('booking.buat');
        Route::get('/booking/status', function () { return "Status Booking"; })->name('booking.status');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
