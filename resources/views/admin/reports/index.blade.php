@extends('admin.layouts.app')
@section('title', 'Laporan Penggunaan Ruangan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Penggunaan Ruangan</h1>
    <div class="flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export_pdf' => 1]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export PDF
        </a>
        <a href="{{ request()->fullUrlWithQuery(['export_excel' => 1]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </a>
    </div>
</div>

{{-- Grafik & Akumulasi Penggunaan Ruangan --}}
<div class="flex flex-col lg:flex-row gap-6 mb-6">
    {{-- Left Card: Grafik Penggunaan --}}
    <div class="w-full lg:w-2/3 bg-white rounded-xl shadow p-6" x-data="{ expanded: false }">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Grafik Penggunaan Ruangan
        </h2>
        
        @php
            $totalBookingsCount = $roomUsage->sum('count');
        @endphp

        <div class="space-y-4">
            {{-- Tampilkan 3 ruangan pertama --}}
            @foreach($roomUsage->take(3) as $index => $item)
                @php
                    $percentage = $totalBookingsCount > 0 ? round(($item['count'] / $totalBookingsCount) * 100, 1) : 0;
                    $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-indigo-500'];
                    $colorClass = $colors[$index % count($colors)];
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700 font-medium">{{ $item['name'] }}</span>
                        <span class="text-gray-500 font-semibold">{{ $item['count'] }} booking ({{ $percentage }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3">
                        <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach

            @if($roomUsage->count() > 3)
                <div x-show="expanded" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="space-y-4 pt-4 border-t border-gray-100">
                    @foreach($roomUsage->slice(3) as $index => $item)
                        @php
                            // Index shifted by 3, so index + 3 for coloring
                            $actualIndex = $index + 3;
                            $percentage = $totalBookingsCount > 0 ? round(($item['count'] / $totalBookingsCount) * 100, 1) : 0;
                            $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-amber-500', 'bg-rose-500', 'bg-indigo-500'];
                            $colorClass = $colors[$actualIndex % count($colors)];
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 font-medium">{{ $item['name'] }}</span>
                                <span class="text-gray-500 font-semibold">{{ $item['count'] }} booking ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3">
                                <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Toggle Chevron --}}
                <div class="pt-2">
                    <div 
                        @click="expanded = !expanded"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 transition cursor-pointer select-none"
                    >
                        <span x-text="expanded ? 'Tampilkan Lebih Sedikit' : 'Tampilkan Semua Ruangan'"></span>

                        <svg 
                            class="w-4 h-4 transform transition-transform duration-200"
                            :class="expanded ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Card: Akumulasi Penggunaan --}}
    <div class="w-full lg:w-1/3 bg-white rounded-xl shadow p-6 flex flex-col justify-center items-center text-center">
        <span class="text-blue-600 bg-blue-100 p-4 rounded-full mb-3 shadow-sm">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </span>
        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Akumulasi Booking</p>
        <h3 class="text-5xl font-extrabold text-blue-600 mt-2">{{ $totalBookingsCount }}</h3>
        <p class="text-xs text-gray-400 mt-3 px-4">Dihitung secara dinamis berdasarkan filter tanggal, ruangan, status, dan pencarian yang sedang aktif.</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Mulai Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Ruangan</label>
            <select name="room_id" class="border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Ruangan</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan/user..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Reset</a>
    </form>
</div>

{{-- Tabel Data --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Peminjam</th>
                    <th class="px-4 py-3">Ruangan</th>
                    <th class="px-4 py-3">Kegiatan</th>
                    <th class="px-4 py-3">Waktu Penggunaan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Verifikator</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $bookings->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $booking->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $booking->user->phone_number ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $booking->room->name }}</td>
                    <td class="px-4 py-3">
                        <p>{{ $booking->activity_name }}</p>
                        <p class="text-gray-400 text-xs">Peserta: {{ $booking->participant_count }} orang</p>
                    </td>
                    <td class="px-4 py-3">
                        <p>{{ $booking->start_time->translatedFormat('d M Y') }}</p>
                        <p class="text-gray-400 text-xs">
                            {{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        @if($booking->status == 'approved')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Disetujui</span>
                        @elseif($booking->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">Menunggu</span>
                        @elseif($booking->status == 'rejected')
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Ditolak</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <p>{{ $booking->verifier ? $booking->verifier->name : '-' }}</p>
                        @if($booking->verified_at)
                            <p class="text-gray-400 text-xs">{{ $booking->verified_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button type="button" onclick="showDetailModal({{ $booking->id }})" class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg text-xs font-medium hover:bg-blue-200">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data laporan yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>

{{-- Modal Detail --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDetailModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Detail Penggunaan Ruangan</h3>
                        <div class="mt-4 space-y-3" id="modalContent">
                            <!-- Detail content will be injected here via JS -->
                            <div class="animate-pulse space-y-4">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeDetailModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Data JSON untuk modal detail
    const bookingsData = @json($bookings->items());
    
    function showDetailModal(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('modalContent');
        const booking = bookingsData.find(b => b.id === id);
        
        if(booking) {
            let statusBadge = '';
            if(booking.status === 'approved') statusBadge = '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Disetujui</span>';
            else if(booking.status === 'pending') statusBadge = '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">Menunggu</span>';
            else if(booking.status === 'rejected') statusBadge = '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Ditolak</span>';
            else statusBadge = '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">Dibatalkan</span>';

            const verifierName = booking.verifier ? booking.verifier.name : '-';
            const verifiedAt = booking.verified_at ? new Date(booking.verified_at).toLocaleString('id-ID') : '-';
            const reason = booking.reason ? booking.reason : '-';
            const phone = booking.user.phone_number ? booking.user.phone_number : '-';
            
            const start = new Date(booking.start_time).toLocaleString('id-ID', {day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
            const end = new Date(booking.end_time).toLocaleString('id-ID', {hour:'2-digit', minute:'2-digit'});

            content.innerHTML = `
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Peminjam</span>
                    <span class="col-span-2 font-medium">${booking.user.name} <span class="text-xs text-gray-400 font-normal">(${phone})</span></span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Ruangan</span>
                    <span class="col-span-2 font-medium">${booking.room.name}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Kegiatan</span>
                    <span class="col-span-2 font-medium">${booking.activity_name}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Jumlah Peserta</span>
                    <span class="col-span-2 font-medium">${booking.participant_count} orang</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Waktu</span>
                    <span class="col-span-2 font-medium">${start} – ${end}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Status</span>
                    <span class="col-span-2">${statusBadge}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-sm border-b pb-2">
                    <span class="text-gray-500">Verifikator</span>
                    <span class="col-span-2 font-medium">${verifierName} <span class="text-xs text-gray-400 font-normal">(${verifiedAt})</span></span>
                </div>
                ${(booking.status === 'rejected' || booking.status === 'cancelled') ? `
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <span class="text-gray-500">Alasan/Catatan</span>
                    <span class="col-span-2 font-medium text-red-600">${reason}</span>
                </div>` : ''}
            `;
        }
        
        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection
