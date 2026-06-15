<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Panitia',
            'email' => 'admin@pakid.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        // 2. Akun Peserta (Mahasiswa)
        User::create([
            'name' => 'Peserta Inovasi',
            'email' => 'peserta@pakid.com',
            'password' => 'password',
            'role' => 'peserta',
        ]);

        // 3. Akun Pengunjung Umum
        User::create([
            'name' => 'Pengunjung Expo',
            'email' => 'pengunjung@pakid.com',
            'password' => 'password',
            'role' => 'pengunjung',
        ]);
    }
}