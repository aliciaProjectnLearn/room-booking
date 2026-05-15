<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'name' => 'Ruang Rapat Utama',
            'capacity' => 50,
            'description' => 'Ruang rapat besar dengan proyektor dan AC',
            'is_active' => true,
        ]);

        Room::create([
            'name' => 'Ruang Kelas A',
            'capacity' => 30,
            'description' => 'Ruang kelas standar',
            'is_active' => true,
        ]);

        Room::create([
            'name' => 'Laboratorium Komputer',
            'capacity' => 40,
            'description' => 'Lab dengan 40 unit PC',
            'is_active' => true,
        ]);

        Room::create([
            'name' => 'Aula Serbaguna',
            'capacity' => 200,
            'description' => 'Aula besar untuk seminar dan acara besar',
            'is_active' => true,
        ]);
    }
}
