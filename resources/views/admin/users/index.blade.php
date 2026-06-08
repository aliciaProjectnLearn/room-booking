@extends('admin.layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2 whitespace-nowrap h-[38px]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah User
    </a>
</div>

{{-- Filter & Pencarian --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm text-gray-600 mb-1">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no WA..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Role</label>
            <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Cari</button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Reset</a>
    </form>
</div>

{{-- Tabel Data --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Informasi User</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tgl Dibuat</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $user->email }}</p>
                        @if($user->phone_number)
                            <p class="text-gray-400 text-xs mt-1">WA: {{ $user->phone_number }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->role == 'admin')
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Admin</span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Guru</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="confirmAction(event, 'Ubah status user ini?')" class="{{ $user->is_active ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} px-3 py-1 rounded-lg text-xs font-medium">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg text-xs font-medium hover:bg-blue-200">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
