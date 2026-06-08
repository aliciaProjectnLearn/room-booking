@extends('admin.layouts.app')
@section('title', 'Riwayat Booking')

@section('content')
<div class="max-w-7xl mx-auto py-8" x-data="{ detailModalOpen: false, modalData: {} }">
    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Booking</h1>
            <p class="mt-1 text-sm text-gray-500">Lihat riwayat booking Anda yang telah selesai, dibatalkan, atau ditolak.</p>
        </div>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium hover:text-brand-500" href="{{ route('guru.dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-brand-500">Riwayat Booking</li>
            </ol>
        </nav>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        {{-- Total Riwayat --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Riwayat</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $totalBooking }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 7V3M16 7V3M4 11H20"/>
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Selesai</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $completed }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $rejected }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Dibatalkan --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Dibatalkan</p>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $cancelled }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 mb-6">
        <form action="{{ route('guru.booking.history') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full"> 
                <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari kode booking atau ruangan..."
                            class="w-full pl-12 pr-4 py-2 rounded-lg border-gray-300 shado  w-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="flex flex-row w-full md:w-auto gap-4">
                <select name="status" class="w-full md:w-auto rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10 py-2 text-sm appearance-none bg-no-repeat" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236b7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-position: right 0.75rem top 50%; background-size: 0.65em auto;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <select name="sort" class="w-full md:w-auto rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-10 py-2 text-sm appearance-none bg-no-repeat" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236b7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-position: right 0.75rem top 50%; background-size: 0.65em auto;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Table --}}
    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 pt-8 pb-8  text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg> 
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat booking.</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai buat booking ruangan baru Anda sekarang.</p>
            <div class="mt-6 mb-8">
                <a href="{{ route('guru.booking.buat') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Buat Booking
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full min-w-max divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Kode Booking</th>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Ruangan</th>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th scope="col" class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            @php
                                $displayStatus = $booking->status === 'approved' ? 'completed' : $booking->status;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->room->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->start_time->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800';
                                        
                                        $statusLabel = [
                                            'completed' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ $statusLabel[$displayStatus] ?? ucfirst($displayStatus) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" 
                                        @click="modalData = { 
                                            id: 'BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}', 
                                            room: '{{ addslashes($booking->room->name ?? '-') }}', 
                                            activity: '{{ addslashes($booking->activity_name) }}',
                                            participant: '{{ $booking->participant_count }}',
                                            date: '{{ $booking->start_time->format('d F Y') }}', 
                                            time: '{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}', 
                                            status: '{{ $statusLabel[$displayStatus] ?? ucfirst($displayStatus) }}',
                                            statusColor: '{{ $color }}',
                                            reason: '{{ addslashes($booking->reason ?? '') }}'
                                        }; detailModalOpen = true"
                                        class="text-blue-600 hover:text-blue-900 font-semibold bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-white">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- Detail Modal --}}
    <template x-teleport="body">
        <div x-show="detailModalOpen" 
             class="fixed inset-0 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4"
             style="z-index: 999999; background-color: rgba(17, 24, 39, 0.6); display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="relative w-full rounded-2xl bg-white shadow-2xl p-6 sm:p-8"
                 style="max-width: 500px; margin: auto;"
                 @click.outside="detailModalOpen = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <h3 class="text-xl font-bold text-gray-900 mb-6">Detail Riwayat</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Kode Booking</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="modalData.id"></span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Ruangan</span>
                        <span class="text-sm font-semibold text-brand-600" x-text="modalData.room"></span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Nama Kegiatan</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="modalData.activity"></span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Peserta</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="modalData.participant + ' Orang'"></span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Tanggal</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="modalData.date"></span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Waktu</span>
                        <span class="text-sm font-semibold text-gray-900" x-text="modalData.time"></span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                              :class="modalData.statusColor" 
                              x-text="modalData.status"></span>
                    </div>

                    <div class="pt-2" x-show="modalData.reason">
                        <span class="block text-sm text-gray-500 mb-1">Alasan Penolakan / Keterangan:</span>
                        <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm border border-red-100" x-text="modalData.reason"></div>
                    </div>
                </div>

                <div class="mt-8">
                    <button @click="detailModalOpen = false" class="w-full inline-flex justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
