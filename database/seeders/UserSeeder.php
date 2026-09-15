<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name'     => 'Administrator Hotel',
            'email'    => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Frontline / Resepsionis
        User::create([
            'name'     => 'Frontline Hotel',
            'email'    => 'frontline@hotel.com',
            'password' => Hash::make('password123'),
            'role'     => 'frontline',
        ]);

        // 3. Akun Media (Konten & Artikel)
        User::create([
            'name'     => 'Media Officer',
            'email'    => 'media@hotel.com',
            'password' => Hash::make('password123'),
            'role'     => 'media',
        ]);
    }
}