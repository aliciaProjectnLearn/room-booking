<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class StatusBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->where(function($query) {
                $query->whereIn('status', ['pending', 'approved'])
                      ->orWhere(function($q) {
                          $q->where('status', 'rejected')
                            ->where('updated_at', '>=', now()->subMinutes(30));
                      });
            })
            ->latest()
            ->paginate(10);

            $pending = Booking::where('user_id', auth()->id())
    ->where('status', 'pending')
    ->count();

$approved = Booking::where('user_id', auth()->id())
    ->where('status', 'approved')
    ->count();

$rejected = Booking::where('user_id', auth()->id())
    ->where('status', 'rejected')
    ->count();

        return view('guru.status', compact(
            'bookings',
            'pending',
            'approved',
            'rejected'
        )); 
    }
}   