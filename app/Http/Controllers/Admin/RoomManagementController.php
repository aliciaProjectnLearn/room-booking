<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $rooms = $query->paginate(15)->withQueryString();

        return view('admin.rooms.index', compact('rooms', 'request'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function toggleStatus(Room $room)
    {
        $room->update(['is_active' => !$room->is_active]);

        $status = $room->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status ruangan berhasil {$status}.");
    }
}
