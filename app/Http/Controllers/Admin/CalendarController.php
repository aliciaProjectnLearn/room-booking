<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $rooms = Room::where('is_active', true)->get();
        return view('admin.calendar', compact('rooms'));
    }

    public function getEvents()
    {
        $bookings = Booking::with('room')
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->map(function ($booking) {
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
            return response()->json(['error' => 'Waktu bentrok atau kurang dari selang waktu 30 menit.'], 422);
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'start_time' => $start,
            'end_time' => $end,
            'activity_name' => $request->title,
            'participant_count' => $request->participants,
            // Admin bookings are automatically approved
            'status' => 'approved', 
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return response()->json(['success' => 'Booking berhasil dibuat.', 'booking' => $booking]);
    }
}
