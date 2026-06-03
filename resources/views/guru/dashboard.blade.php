@extends('admin.layouts.app')
@section('title', 'Dashboard Guru')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Guru</h1>

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
            <a href="#"
               class="text-xs text-blue-500 hover:underline mt-1 block">
                Lihat status booking anda →
            </a>
        @endif
    </div>
</div>

{{-- Jadwal Hari Ini --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        Jadwal Hari Ini — {{ now()->translatedFormat('l, d F Y') }}
    </h2>
    @if($jadwalHariIni->isEmpty())
        <p class="text-gray-400 text-sm">Tidak ada booking yang disetujui untuk hari ini.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Ruangan</th>
                        <th class="px-4 py-3">Kegiatan</th>
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($jadwalHariIni as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $booking->room->name }}</td>
                        <td class="px-4 py-3">{{ $booking->activity_name }}</td>
                        <td class="px-4 py-3">{{ $booking->user->name }}</td>
                        <td class="px-4 py-3">
                            {{ $booking->start_time->format('H:i') }} –
                            {{ $booking->end_time->format('H:i') }}
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
