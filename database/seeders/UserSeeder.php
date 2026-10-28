<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@mail.com',
            'nomorTelepon' => '081234567890',
            'kata_sandi' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User dummy pakai factory
        User::factory()->count(5)->create([
            'role' => 'mahasiswa',
        ]);
    }
}
