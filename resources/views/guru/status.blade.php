@extends('admin.layouts.app')

@section('title', 'Status Booking')

@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-white">
        Status Booking
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium hover:text-brand-500"
                   href="{{ route('guru.dashboard') }}">
                    Dashboard /
                </a>
            </li>
            <li class="font-medium text-brand-500">
                Status Booking
            </li>
        </ol>
    </nav>
</div>

{{-- Summary Card --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    {{-- Menunggu Verifikasi --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Menunggu Verifikasi
                </p>
                <h3 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $pending }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Disetujui --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Disetujui
                </p>
                <h3 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $approved }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Ditolak --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Ditolak
                </p>
                <h3 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $rejected }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Booking --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Total Booking
                </p>
                <h3 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $bookings->total() }}
                </h3>
            </div>

            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M8 7V3M16 7V3M4 11H20"/>
                    <rect x="4" y="5" width="16" height="16" rx="2"/>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- List Booking --}}
<div class="space-y-8">

    @forelse($bookings as $booking)

        @php

            $statusColor = match($booking->status) {
                'approved' => 'green',
                'rejected' => 'red',
                default => 'yellow'
            };

        @endphp

        <div
            class="overflow-hidden rounded-3xl border-l-4
            {{ $statusColor == 'green' ? 'border-green-500' : '' }}
            {{ $statusColor == 'red' ? 'border-red-500' : '' }}
            {{ $statusColor == 'yellow' ? 'border-yellow-500' : '' }}
            bg-white border border-gray-100 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

            <div class="p-6">

                <div class="flex flex-col gap-4 lg:flex-row lg:justify-between">

                    <div class="flex-1">

                        <h3 class="text-xl font-bold text-gray-800">
                            {{ $booking->activity_name }}
                        </h3>

                        <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-600">

                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"/>
                                    <circle cx="12" cy="11" r="3"/>
                                </svg>
                                {{ $booking->room->name }}
                            </span>

                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/>
                                    <circle cx="10" cy="7" r="4"/>
                                    <circle cx="17" cy="7" r="4"/>
                                </svg>
                                {{ $booking->participant_count }} Peserta
                            </span>

                        </div>

                        <div class="mt-4 rounded-2xl border border-gray-100 bg-gray-50 p-5">

                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">

                                <svg class="w-5 h-5 text-brand-500"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path d="M8 7V3M16 7V3M4 11H20"/>
                                    <rect x="4" y="5" width="16" height="16" rx="2"/>

                                </svg>

                                Jadwal Booking

                            </div>

                            <div class="mt-1 font-medium text-gray-800">
                                {{ $booking->start_time->format('d F Y') }}
                            </div>

                            <div class="text-sm text-gray-600">
                                {{ $booking->start_time->format('H:i') }}
                                -
                                {{ $booking->end_time->format('H:i') }}
                            </div>

                        </div>

                    </div><br>

                    <div class="lg:text-right">

                        @if($booking->status == 'pending')
                            <span class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-800">
                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                    Menunggu Verifikasi
                            </span>
                        @elseif($booking->status == 'approved')
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-800">
                                   <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-800">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                Ditolak
                            </span>
                        @endif

                        <div class="mt-3 text-xs text-gray-500">
                            Diajukan:
                            <br>
                            {{ $booking->created_at->format('d M Y H:i') }}
                        </div>

                    </div>

                </div>

                {{-- Timeline --}}
                <div class="mt-6 border-t pt-5">

                    <div class="flex flex-wrap items-center gap-4 text-sm">

                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            <span>Pengajuan Terkirim</span>
                        </div>

                        <div class="h-px flex-1 bg-gray-200"></div>

                        @if($booking->status == 'pending')

                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                                <span>Menunggu Verifikasi</span>
                            </div>

                        @elseif($booking->status == 'approved')

                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span>Diverifikasi</span>
                            </div>

                            <div class="h-px flex-1 bg-gray-200"></div>

                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                <span>Disetujui</span>
                            </div>

                        @else

                            <div class="flex items-center gap-2">
                                <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                <span>Ditolak</span>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div><br>

    @empty

        <div class="rounded-3xl bg-white p-12 text-center shadow-sm">

            <div class="text-6xl">
                📅
            </div>

            <h3 class="mt-4 text-xl font-semibold text-gray-700">
                Belum Ada Booking
            </h3>

            <p class="mt-2 text-gray-500">
                Silakan lakukan booking ruangan terlebih dahulu.
            </p>

            <a href="{{ route('guru.jadwal') }}"
               class="mt-5 inline-flex rounded-xl bg-brand-500 px-5 py-3 text-white">
                Buat Booking
            </a>

        </div>

    @endforelse

</div>

@if($bookings->hasPages())
<div class="mt-8">
    {{ $bookings->links() }}
</div>
@endif

@endsection