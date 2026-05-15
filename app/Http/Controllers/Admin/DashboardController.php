<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total semua ruangan
        $totalRuangan = Room::count();

        // Total ruangan aktif
        $ruanganAktif = Room::where('is_active', true)->count();

        // Total booking hari ini
        $bookingHariIni = Booking::whereDate('start_time', today())
            ->where('status', 'approved')
            ->count();

        // Total booking menunggu verifikasi
        $bookingPending = Booking::pending()->count();

        // Daftar booking hari ini beserta relasi user & ruangan
        $jadwalHariIni = Booking::with(['user', 'room'])
            ->whereDate('start_time', today())
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->get();

        // Booking per ruangan bulan ini (untuk grafik)
        $bookingPerRuangan = Room::withCount([
            'bookings as total_booking' => function ($query) {
                $query->whereMonth('start_time', now()->month)
                      ->whereYear('start_time', now()->year)
                      ->where('status', 'approved');
            }
        ])->get();

        return view('admin.dashboard', compact(
            'totalRuangan',
            'ruanganAktif',
            'bookingHariIni',
            'bookingPending',
            'jadwalHariIni',
            'bookingPerRuangan'
        ));
    }
}