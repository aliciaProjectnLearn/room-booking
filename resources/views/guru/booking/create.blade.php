@extends('admin.layouts.app')
@section('title', 'Buat Booking Baru')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Booking Baru</h1>
            <p class="mt-1 text-sm text-gray-500">Isi formulir di bawah ini untuk mengajukan peminjaman ruangan.</p>
        </div>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium hover:text-brand-500" href="{{ route('guru.dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-brand-500">Buat Booking</li>
            </ol>
        </nav>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8" x-data="{
        isSubmitting: false,
        rooms: {{ Js::from($rooms) }},
        eventRoomId: '{{ old('room_id') }}',
        eventParticipants: '{{ old('participants') }}',
        colors: ['Primary', 'Success', 'Warning', 'Danger'],
        get selectedRoom() {
            return this.rooms.find(r => r.id == this.eventRoomId) || null;
        },
        get eventLevel() {
            if (!this.selectedRoom) return 'Primary';
            const index = this.rooms.findIndex(r => r.id == this.eventRoomId);
            return this.colors[index % this.colors.length];
        },
        async submitForm(e) {
            this.isSubmitting = true;
            Swal.fire({
                title: 'Mengirim Booking...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let formData = new FormData(e.target);
            let token = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

            try {
                let response = await fetch('{{ route('guru.booking.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let data = await response.json();

                if (!response.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.error || data.message || 'Terjadi kesalahan sistem.'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.success || 'Booking berhasil diajukan!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route('guru.booking.status') }}';
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan atau server.'
                });
            } finally {
                this.isSubmitting = false;
            }
        }
    }">
        <form @submit.prevent="submitForm" class="space-y-6">
            @csrf

            <!-- Judul Event -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Judul Kegiatan</label>
                <input name="title" value="{{ old('title') }}" required type="text" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Contoh: Rapat Koordinasi" />
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Ruangan & Peserta -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Ruangan</label>
                    <select name="room_id" x-model="eventRoomId" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">-- Pilih Ruangan --</option>
                        <template x-for="room in rooms" :key="room.id">
                            <option :value="room.id" x-text="room.name + ' (Kapasitas: ' + room.capacity + ')'" :selected="room.id == eventRoomId"></option>
                        </template>
                    </select>
                    @error('room_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    
                    <p class="mt-1 text-xs text-gray-500" x-show="selectedRoom" style="display: none;">
                        Warna kalender ruangan ini: <span class="font-bold uppercase" x-text="eventLevel" :class="{
                            'text-blue-500': eventLevel === 'Primary',
                            'text-green-500': eventLevel === 'Success',
                            'text-yellow-500': eventLevel === 'Warning',
                            'text-red-500': eventLevel === 'Danger'
                        }"></span>
                    </p>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Jumlah Peserta</label>
                    <input name="participants" x-model="eventParticipants" required type="number" min="1" :max="selectedRoom ? selectedRoom.capacity : ''" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="0" />
                    @error('participants') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-red-500" x-show="selectedRoom && eventParticipants > selectedRoom.capacity" style="display: none;">
                        Melebihi kapasitas maksimal (<span x-text="selectedRoom.capacity"></span>)
                    </p>
                </div>
            </div>

            <!-- Mulai & Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Waktu Mulai</label>
                    <input name="start" value="{{ old('start') }}" required type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm focus:border-brand-500 focus:ring-brand-500" />
                    @error('start') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Waktu Selesai</label>
                    <input name="end" value="{{ old('end') }}" required type="datetime-local" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-sm focus:border-brand-500 focus:ring-brand-500" />
                    @error('end') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8">
                <a href="{{ route('guru.dashboard') }}" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Batal
                </a>
                <button type="submit" :disabled="isSubmitting" class="inline-flex justify-center items-center gap-2 rounded-lg border border-transparent bg-brand-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Buat Booking</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Mengirim...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
