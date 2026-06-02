<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $rooms = Room::where('is_active', true)->get();
        return view('guru.jadwal', compact('rooms'));
    }

    public function getEvents()
    {
        $bookings = Booking::with('room')
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->map(function ($booking) {
                // Map the room id to color logic
                $colors = ['Primary', 'Success', 'Warning', 'Danger'];
                $rooms = Room::where('is_active', true)->pluck('id')->toArray();
                $index = array_search($booking->room_id, $rooms);
                $color = $index !== false ? $colors[$index % count($colors)] : 'Primary';

                return [
                    'id' => $booking->id,
                    'title' => $booking->activity_name . ($booking->status === 'pending' ? ' (Pending)' : ''),
                    'start' => $booking->start_time->format('Y-m-d\TH:i'),
                    'end' => $booking->end_time->format('Y-m-d\TH:i'),
                    'extendedProps' => [
                        'calendar' => $color,
                        'roomId' => $booking->room_id,
                        'participants' => $booking->participant_count
                    ]
                ];
            });

        return response()->json($bookings);
    }

    public function store(Request $request, \App\Services\WhatsAppService $waService)
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
            return response()->json(['error' => 'Jumlah peserta melebihi kapasitas ruangan.'], 422);
        }

        // Check overlaps with 30 mins buffer
        $start = \Carbon\Carbon::parse($request->start);
        $end = \Carbon\Carbon::parse($request->end);

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
            return response()->json(['error' => 'Waktu bentrok atau kurang dari selang waktu 30 menit dengan jadwal lain.'], 422);
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

        // Send WhatsApp to Admin Cia & Guru
        $adminPhone = '087882539342';
        $guruPhone = auth()->user()->phone_number;
        
        $msgAdmin = "Halo Admin Cia,\nAda permohonan booking ruangan baru dari " . auth()->user()->name . " untuk ruang {$room->name} pada {$start->format('d/m/Y H:i')}. Mohon segera diverifikasi.";
        $msgGuru = "Halo " . auth()->user()->name . ",\nPermohonan booking ruang {$room->name} pada {$start->format('d/m/Y H:i')} berhasil dikirim dan sedang menunggu verifikasi.";

        $waService->sendMessage($adminPhone, $msgAdmin);
        if ($guruPhone) {
            $waService->sendMessage($guruPhone, $msgGuru);
        }

        return response()->json(['success' => 'Booking berhasil diajukan, menunggu verifikasi.', 'booking' => $booking]);
    }
}
