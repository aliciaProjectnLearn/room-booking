<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = User::where('role', 'guru')->first();
        $room = Room::first();

        if ($guru && $room) {
            // Pending booking
            Booking::create([
                'user_id' => $guru->id,
                'room_id' => $room->id,
                'start_time' => Carbon::tomorrow()->setHour(8)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::tomorrow()->setHour(10)->setMinute(0)->setSecond(0),
                'activity_name' => 'Rapat Koordinasi Guru',
                'status' => 'pending',
            ]);

            // Approved booking
            Booking::create([
                'user_id' => $guru->id,
                'room_id' => $room->id,
                'start_time' => Carbon::tomorrow()->addDay()->setHour(13)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::tomorrow()->addDay()->setHour(15)->setMinute(0)->setSecond(0),
                'activity_name' => 'Persiapan Ujian',
                'status' => 'approved',
            ]);
        }
    }
}
