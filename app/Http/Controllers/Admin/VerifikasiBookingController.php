<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class VerifikasiBookingController extends Controller
{
    // Tampilkan semua booking pending
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room'])
            ->pending()
            ->orderBy('start_time');

        // Filter berdasarkan ruangan (opsional)
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Filter berdasarkan tanggal (opsional)
        if ($request->filled('tanggal')) {
            $query->whereDate('start_time', $request->tanggal);
        }

        $bookings = $query->paginate(10);
        $ruangan  = Room::where('is_active', true)->get();

        return view('admin.verifikasi.index', compact('bookings', 'ruangan'));
    }

    // Setujui booking
    public function setujui(Booking $booking)
    {
        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        // Cek apakah ada booking lain yang bentrok di ruangan & waktu yang sama
        $bentrok = Booking::where('room_id', $booking->room_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($booking) {
                $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                      ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                      ->orWhere(function ($q) use ($booking) {
                          $q->where('start_time', '<=', $booking->start_time)
                            ->where('end_time', '>=', $booking->end_time);
                      });
            })->exists();

        if ($bentrok) {
            return back()->with('error', 'Tidak bisa disetujui, waktu bentrok dengan booking lain.');
        }

        $booking->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil disetujui.');
    }

    // Tolak booking
    public function tolak(Booking $booking)
    {
        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        $booking->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil ditolak.');
    }
}