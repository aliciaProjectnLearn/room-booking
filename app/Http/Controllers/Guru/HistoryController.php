<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $thirtyDaysAgo = now()->subDays(30);
        $userId = auth()->id();

        // Main query for list
        $query = Booking::with('room')
            ->where('user_id', $userId)
            ->where('start_time', '>=', $thirtyDaysAgo)
            ->where(function ($q) {
                $q->whereIn('status', ['rejected', 'cancelled'])
                  ->orWhere(function ($q2) {
                      // "selesai" (completed)
                      $q2->where('status', 'approved')->where('end_time', '<=', now());
                  });
            });

        // Base query for summary cards
        $baseQuery = clone $query;

        // Calculate summaries
        $totalBooking = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', 'approved')->count();
        $cancelled = (clone $baseQuery)->where('status', 'cancelled')->count();
        $rejected = (clone $baseQuery)->where('status', 'rejected')->count();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            // Handle if user types "BK-0001" etc
            $idSearch = ltrim(str_ireplace('bk-', '', $search), '0');
            if (empty($idSearch)) {
                $idSearch = $search;
            }

            $query->where(function ($q) use ($search, $idSearch) {
                $q->where('id', 'like', "%{$idSearch}%")
                  ->orWhereHas('room', function ($qRoom) use ($search) {
                      $qRoom->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('status', 'approved');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Sorting
        if ($request->sort === 'oldest') {
            $query->oldest('start_time');
        } else {
            $query->latest('start_time');
        }

        $bookings = $query->paginate(5)->withQueryString();

        return view('guru.history', compact(
            'totalBooking',
            'completed',
            'cancelled',
            'rejected',
            'bookings'
        ));
    }
}
