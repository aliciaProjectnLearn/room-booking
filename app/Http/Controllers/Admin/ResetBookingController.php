<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class ResetBookingController extends Controller
{
    // Tampilkan semua booking yang sudah disetujui (bisa di-reset)
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room'])
            ->approved()
            ->orderBy('start_time', 'desc');

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

        return view('admin.reset.index', compact('bookings', 'ruangan'));
    }

    // Reset booking: kembalikan status dari approved ke pending
    public function reset(Booking $booking)
    {
        // Hanya booking approved yang bisa di-reset
        if (!$booking->bisaDireset()) {
            return back()->with('error', 'Hanya booking yang sudah disetujui yang bisa di-reset.');
        }

        $booking->update(['status' => 'pending']);

        return back()->with('success', 'Booking berhasil di-reset ke pending.');
    }
}