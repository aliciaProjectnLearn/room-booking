@extends('admin.layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

{{-- Kartu Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-900 dark:border-gray-800 border border-gray-200 rounded-xl shadow-theme-md p-6">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ruangan</p>
        <p class="text-title-md font-bold text-brand-600 dark:text-brand-500 mt-2">{{ $totalRuangan }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 dark:border-gray-800 border border-gray-200 rounded-xl shadow-theme-md p-6">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ruangan Aktif</p>
        <p class="text-title-md font-bold text-success-500 mt-2">{{ $ruanganAktif }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 dark:border-gray-800 border border-gray-200 rounded-xl shadow-theme-md p-6">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Hari Ini</p>
        <p class="text-title-md font-bold text-purple-600 mt-2">{{ $bookingHariIni }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 dark:border-gray-800 border border-gray-200 rounded-xl shadow-theme-md p-6">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Verifikasi</p>
        <p class="text-title-md font-bold text-warning-500 mt-2">{{ $bookingPending }}</p>
        @if($bookingPending > 0)
            <a href="{{ route('admin.verifikasi.index') }}"
               class="text-xs text-blue-500 hover:underline mt-1 block">
                Lihat sekarang →
            </a>
        @endif
    </div>
</div>

{{-- Request Booking Hari Ini --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        Request Booking Hari Ini — {{ now()->translatedFormat('l, d F Y') }}
    </h2>
    @if($requestHariIni->isEmpty())
        <p class="text-gray-400 text-sm">Tidak ada request booking untuk hari ini.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Ruangan</th>
                        <th class="px-4 py-3">Kegiatan</th>
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Waktu Mulai</th>
                        <th class="px-4 py-3">Waktu Selesai</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($requestHariIni as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $booking->room->name }}</td>
                        <td class="px-4 py-3">{{ $booking->activity_name }}</td>
                        <td class="px-4 py-3">{{ $booking->user->name }}</td>
                        <td class="px-4 py-3">{{ $booking->start_time->format('H:i') }}</td>
                        <td class="px-4 py-3">{{ $booking->end_time->format('H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $booking->status == 'approved' ? 'bg-green-100 text-green-700' : ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($booking->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Penggunaan Ruangan Bulan Ini --}}
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        Penggunaan Ruangan — {{ now()->translatedFormat('F Y') }}
    </h2>
    <div class="space-y-3">
        @foreach($bookingPerRuangan as $ruangan)
        <div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">{{ $ruangan->name }}</span>
                <span class="font-semibold">{{ $ruangan->total_booking }} booking</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                @php
                    $maxBooking = $bookingPerRuangan->max('total_booking');
                    $persen = $maxBooking > 0 ? ($ruangan->total_booking / $maxBooking) * 100 : 0;
                @endphp
                <div class="bg-blue-500 h-2 rounded-full"
                     style="width: {{ $persen }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection