<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Keisha',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '081234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Cia',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '087882539342',
            'is_active' => true,
        ]); 

        // Guru 1
        User::create([
            'name' => 'Adang Nugroho',
            'email' => 'adang@example.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone_number' => '083167383307',
            'is_active' => true,
        ]);

        // Guru 2
        User::create([
            'name' => 'Boya Rizky Agung',
            'email' => 'boya@example.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone_number' => '081211223344',
            'is_active' => true,
        ]);
    }
}
