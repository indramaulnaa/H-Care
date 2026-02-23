<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // AKUN 1: ADMIN DINKES (Bisa lihat semua)
            [
                'name' => 'Admin Dinas Kesehatan',
                'username' => 'admin_dinkes',
                'email' => 'admin@dinkes.batang.go.id',
                'password' => Hash::make('password123'), // Passwordnya: password123
                'role' => 'admin_dinkes',
                'nama_unit' => 'Dinas Kesehatan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            
            // AKUN 2: ADMIN PUSKESMAS BANDAR (Hanya lihat Bandar)
            [
                'name' => 'Admin Puskesmas Bandar',
                'username' => 'pusk_bandar',
                'email' => 'admin@bandar.puskesmas.id',
                'password' => Hash::make('password123'),
                'role' => 'admin_puskesmas',
                'nama_unit' => 'Puskesmas Bandar',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($data);
    }
}