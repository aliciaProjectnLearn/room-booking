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
    public function setujui(Booking $booking, \App\Services\WhatsAppService $waService)
    {
        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        // Cek apakah ada booking lain yang bentrok di ruangan & waktu yang sama (dengan buffer 30 menit)
        $start = \Carbon\Carbon::parse($booking->start_time);
        $end = \Carbon\Carbon::parse($booking->end_time);

        $bufferStart = $start->copy()->subMinutes(30);
        $bufferEnd = $end->copy()->addMinutes(30);

        $bentrok = Booking::where('room_id', $booking->room_id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($bufferStart, $bufferEnd) {
                $query->whereBetween('start_time', [$bufferStart, $bufferEnd])
                      ->orWhereBetween('end_time', [$bufferStart, $bufferEnd])
                      ->orWhere(function ($q) use ($bufferStart, $bufferEnd) {
                          $q->where('start_time', '<=', $bufferStart)
                            ->where('end_time', '>=', $bufferEnd);
                      });
            })->exists();

        if ($bentrok) {
            return back()->with('error', 'Tidak bisa disetujui, waktu bentrok atau kurang dari selang waktu 30 menit dengan jadwal lain.');
        }

        $booking->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Send WhatsApp to Guru
        if ($booking->user && $booking->user->phone_number) {
            $guruPhone = $booking->user->phone_number;
            $msgGuru = "Halo {$booking->user->name},\nSelamat, permohonan booking untuk ruang {$booking->room->name} pada {$start->format('d/m/Y H:i')} telah DISETUJUI oleh Admin.";
            $waService->sendMessage($guruPhone, $msgGuru);
        }

        return back()->with('success', 'Booking berhasil disetujui.');
    }

    // Tolak booking
    public function tolak(Booking $booking, \App\Services\WhatsAppService $waService)
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

        // Send WhatsApp to Guru
        if ($booking->user && $booking->user->phone_number) {
            $guruPhone = $booking->user->phone_number;
            $msgGuru = "Halo {$booking->user->name},\nMohon maaf, permohonan booking untuk ruang {$booking->room->name} pada " . $booking->start_time->format('d/m/Y H:i') . " telah DITOLAK oleh Admin.";
            $waService->sendMessage($guruPhone, $msgGuru);
        }

        return back()->with('success', 'Booking berhasil ditolak.');
    }
}