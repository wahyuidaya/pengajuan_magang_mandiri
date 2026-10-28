<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('12345677');

        $users = [
            [
                'username' => 'Mahasiswa',
                'role' => 'mahasiswa',
            ],
            [
                'username' => 'Mitra Perusahaan',
                'role' => 'mitra_perusahaan',
            ],
            [
                'username' => 'Dosen Pembimbing',
                'role' => 'dosen_pembimbing',
            ],
            [
                'username' => 'Staf Prodi',
                'role' => 'staf_prodi',
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['username' => $data['username']],
                [
                    'id_user' => Str::uuid(),
                    'password_hash' => $password,
                    'role' => $data['role'],
                ]
            );
        }
    }
}