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
        $users = [
            [
                'username' => 'mahasiswa',
                'email'    => 'mahasiswa@gmail.com',
                'password' => Hash::make('mahasiswa123'),
                'role'     => 'mahasiswa',
            ],
            [
                'username' => 'stafprodi',
                'email'    => 'stafprodi@gmail.com',
                'password' => Hash::make('stafprodi123'),
                'role'     => 'staf_prodi',
            ],
            [
                'username' => 'dosen',
                'email'    => 'dosen@gmail.com',
                'password' => Hash::make('dosen123'),
                'role'     => 'dosen_pembimbing',
            ],
            [
                'username' => 'mitra',
                'email'    => 'mitra@gmail.com',
                'password' => Hash::make('mitra123'),
                'role'     => 'mitra_perusahaan',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['role' => $data['role']],
                [
                    'username'      => $data['username'],
                    'email'         => $data['email'],
                    'password_hash' => $data['password'],
                ]
            );
        }
    }
}
