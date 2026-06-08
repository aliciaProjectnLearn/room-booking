<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'start_time',
        'end_time',
        'activity_name',
        'participant_count',
        'status',
        'verified_by',
        'verified_at',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'start_time'  => 'datetime',
            'end_time'    => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Scope filter by status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Cek apakah booking bisa di-reset (hanya yg approved)
    public function bisaDireset(): bool
    {
        return $this->status === 'approved';
    }
}