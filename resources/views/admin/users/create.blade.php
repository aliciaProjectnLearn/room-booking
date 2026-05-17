@extends('admin.layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Tambah User Baru</h1>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden max-w-3xl">
    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('phone_number') border-red-500 @enderror">
                @error('phone_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('role') border-red-500 @enderror">
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            
            <div class="col-span-1 md:col-span-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">User Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end border-t pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700">Simpan User</button>
        </div>
    </form>
</div>
@endsection
