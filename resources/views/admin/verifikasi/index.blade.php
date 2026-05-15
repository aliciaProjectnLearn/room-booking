@extends('admin.layouts.app')
@section('title', 'Verifikasi Booking')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Verifikasi Booking</h1>

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.verifikasi.index') }}"
          class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Ruangan</label>
            <select name="room_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Ruangan</option>
                @foreach($ruangan as $r)
                    <option value="{{ $r->id }}"
                        {{ request('room_id') == $r->id ? 'selected' : '' }}>
                        {{ $r->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Filter
        </button>
        <a href="{{ route('admin.verifikasi.index') }}"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">
            Reset
        </a>
    </form>
</div>

{{-- Tabel Booking Pending --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Pemohon</th>
                <th class="px-4 py-3">Ruangan</th>
                <th class="px-4 py-3">Kegiatan</th>
                <th class="px-4 py-3">Waktu</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium">{{ $booking->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $booking->user->phone_number ?? '-' }}</p>
                </td>
                <td class="px-4 py-3">{{ $booking->room->name }}</td>
                <td class="px-4 py-3">{{ $booking->activity_name }}</td>
                <td class="px-4 py-3">
                    <p>{{ $booking->start_time->translatedFormat('d M Y') }}</p>
                    <p class="text-gray-400 text-xs">
                        {{ $booking->start_time->format('H:i') }} –
                        {{ $booking->end_time->format('H:i') }}
                    </p>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        {{-- Tombol Setujui --}}
                        <form method="POST"
                              action="{{ route('admin.verifikasi.setujui', $booking) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    onclick="confirmAction(event, 'Setujui booking ini?')"
                                    class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-600">
                                Setujui
                            </button>
                        </form>
                        {{-- Tombol Tolak --}}
                        <form method="POST"
                              action="{{ route('admin.verifikasi.tolak', $booking) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    onclick="confirmAction(event, 'Tolak booking ini?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600">
                                Tolak
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                    Tidak ada booking yang menunggu verifikasi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($bookings->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection