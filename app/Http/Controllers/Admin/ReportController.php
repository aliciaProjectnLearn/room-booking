<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel; // If using maatwebsite/excel for Excel export
// use App\Exports\BookingsExport; 

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Query base
        $query = Booking::with(['user', 'room', 'verifier'])->latest();

        // Filter berdasarkan tanggal (range: start_date to end_date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_time', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('end_time', '<=', $request->end_date);
        }

        // Filter berdasarkan bulan & tahun
        if ($request->filled('month')) {
            $query->whereMonth('start_time', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('start_time', $request->year);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan search (activity_name atau nama user)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('activity_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Summary cards data
        $summary = [
            'total' => (clone $query)->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];

        // Export checks
        if ($request->has('export_pdf')) {
            $bookings = $query->get();
            $pdf = Pdf::loadView('admin.reports.pdf', compact('bookings', 'request'))
                      ->setPaper('a4', 'landscape');
            return $pdf->download('Laporan_Penggunaan_Ruangan_' . date('Y-m-d_H-i') . '.pdf');
        }

        if ($request->has('export_excel')) {
            $bookings = $query->get();
            $filename = 'Laporan_Penggunaan_Ruangan_' . date('Y-m-d_H-i') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for proper Excel encoding
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // CSV Headers
                fputcsv($file, [
                    'No',
                    'Nama Peminjam',
                    'Nomor HP',
                    'Ruangan',
                    'Nama Kegiatan',
                    'Jumlah Peserta',
                    'Tanggal',
                    'Waktu Mulai',
                    'Waktu Selesai',
                    'Status',
                    'Verifikator',
                    'Tanggal Verifikasi'
                ]);

                foreach ($bookings as $index => $booking) {
                    $status = '';
                    if ($booking->status == 'approved') {
                        $status = 'Disetujui';
                    } elseif ($booking->status == 'pending') {
                        $status = 'Menunggu';
                    } elseif ($booking->status == 'rejected') {
                        $status = 'Ditolak';
                    } else {
                        $status = 'Dibatalkan';
                    }

                    fputcsv($file, [
                        $index + 1,
                        $booking->user->name,
                        $booking->user->phone_number ?? '-',
                        $booking->room->name,
                        $booking->activity_name,
                        $booking->participant_count . ' orang',
                        $booking->start_time->translatedFormat('Y-m-d'),
                        $booking->start_time->format('H:i'),
                        $booking->end_time->format('H:i'),
                        $status,
                        $booking->verifier->name ?? '-',
                        $booking->verified_at ? $booking->verified_at->format('Y-m-d H:i') : '-'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $rooms = Room::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Calculate Room Usage dynamically based on active filters
        $usageCounts = (clone $query)
            ->reorder()
            ->selectRaw('room_id, count(*) as total')
            ->groupBy('room_id')
            ->pluck('total', 'room_id')
            ->toArray();

        $roomUsage = $rooms->map(function($room) use ($usageCounts) {
            return [
                'name' => $room->name,
                'count' => $usageCounts[$room->id] ?? 0,
            ];
        });

        return view('admin.reports.index', compact('bookings', 'summary', 'rooms', 'users', 'roomUsage', 'request'));
    }
}
