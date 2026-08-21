<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name'     => 'Administrator Hotel',
            'email'    => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Tamu
        User::create([
            'name'     => 'Tamu Hotel',
            'email'    => 'tamu@hotel.com',
            'password' => Hash::make('password123'),
            'role'     => 'tamu',
        ]);
    }
}