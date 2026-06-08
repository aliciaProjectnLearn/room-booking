<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where('is_active', true)->get();
        return view('guru.booking.create', compact('rooms'));
    }

    public function store(Request $request, WhatsAppService $waService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'participants' => 'required|integer|min:1',
            'start' => 'required|date|after_or_equal:now',
            'end' => 'required|date|after:start',
        ]);

        $room = Room::findOrFail($request->room_id);
        if ($request->participants > $room->capacity) {
            if ($request->expectsJson()) return response()->json(['error' => 'Jumlah peserta melebihi kapasitas ruangan.'], 422);
            return back()->withInput()->with('error', 'Jumlah peserta melebihi kapasitas ruangan.');
        }

        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $bufferStart = $start->copy()->subMinutes(30);
        $bufferEnd = $end->copy()->addMinutes(30);

        $bentrok = Booking::where('room_id', $room->id)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($query) use ($bufferStart, $bufferEnd) {
                $query->whereBetween('start_time', [$bufferStart, $bufferEnd])
                      ->orWhereBetween('end_time', [$bufferStart, $bufferEnd])
                      ->orWhere(function ($q) use ($bufferStart, $bufferEnd) {
                          $q->where('start_time', '<=', $bufferStart)
                            ->where('end_time', '>=', $bufferEnd);
                      });
            })->exists();

        if ($bentrok) {
            if ($request->expectsJson()) return response()->json(['error' => 'Waktu bentrok atau kurang dari selang waktu 30 menit dengan jadwal lain.'], 422);
            return back()->withInput()->with('error', 'Waktu bentrok atau kurang dari selang waktu 30 menit dengan jadwal lain.');
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'start_time' => $start,
            'end_time' => $end,
            'activity_name' => $request->title,
            'participant_count' => $request->participants,
            'status' => 'pending',
        ]);

        // Send WhatsApp to Admin & Guru
        $adminPhone = '087882539342';
        $guruPhone = auth()->user()->phone_number;
        
        $msgAdmin = "Halo Admin Cia,\nAda permohonan booking ruangan baru dari " . auth()->user()->name . " untuk ruang {$room->name} pada {$start->format('d/m/Y H:i')}. Mohon segera diverifikasi.";
        $msgGuru = "Halo " . auth()->user()->name . ",\nPermohonan booking ruang {$room->name} pada {$start->format('d/m/Y H:i')} berhasil dikirim dan sedang menunggu verifikasi.";

        $waService->sendMessage($adminPhone, $msgAdmin);
        if ($guruPhone) {
            $waService->sendMessage($guruPhone, $msgGuru);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Booking berhasil diajukan, menunggu verifikasi.']);
        }

        return redirect()->route('guru.booking.status')->with('success', 'Booking berhasil diajukan, menunggu verifikasi.');
    }

    public function cancel(Booking $booking, WhatsAppService $waService)
    {
        // Hanya user yang bersangkutan yang boleh membatalkan, dan status masih bisa dibatalkan
        if ($booking->user_id !== auth()->id() || in_array($booking->status, ['rejected', 'cancelled', 'completed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        // Notifikasi ke Admin
        $adminPhone = '087882539342';
        $msgAdmin = "Halo Admin Cia,\nPengguna " . auth()->user()->name . " telah MEMBATALKAN permohonan booking untuk ruang {$booking->room->name} pada " . $booking->start_time->format('d/m/Y H:i') . ".";
        
        $waService->sendMessage($adminPhone, $msgAdmin);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
