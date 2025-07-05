<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account - only for admin role login
        User::create([
            'name' => 'Administrator DAOP 6',
            'email' => 'admin@arsip.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // User account - only for user role login
        User::create([
            'name' => 'Pegawai DAOP 6',
            'email' => 'user@arsip.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);
    }
}
