@extends('admin.layouts.app')
@section('title', 'Edit Ruangan')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.rooms.index') }}" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Edit Ruangan</h1>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden max-w-3xl">
    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $room->name) }}" required placeholder="Contoh: Lab Komputer 1, Aula Utama" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas (Orang)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" placeholder="Contoh: 30" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('capacity') border-red-500 @enderror">
                @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Ruangan</label>
                <textarea name="description" rows="4" placeholder="Tuliskan detail atau fasilitas yang ada di ruangan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('description') border-red-500 @enderror">{{ old('description', $room->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Ruangan Aktif (Dapat Dibooking)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end border-t pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700">Perbarui Ruangan</button>
        </div>
    </form>
</div>
@endsection
