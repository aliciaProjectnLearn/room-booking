<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Booking yang aktif (approved & belum lewat)
    public function activeBookings()
    {
        return $this->hasMany(Booking::class)
            ->where('status', 'approved')
            ->where('end_time', '>=', now());
    }
}