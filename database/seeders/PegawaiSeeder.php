<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        // KITA SET TANGGAL HARI INI: Februari 2026
        // Rumus Pensiun: Lahir + 58 Tahun
        
        $data = [
            // KASUS 1: PENSIUN BULAN INI (Februari 2026)
            // Lahir: Februari 1968
            [
                'nip' => '19680215 199003 1 001',
                'nama_lengkap' => 'Budi Santoso (Pensiun Bulan Ini)',
                'tanggal_lahir' => '1968-02-15', 
                'jabatan' => 'Staf Administrasi',
                'unit_kerja' => 'Puskesmas Bandar',
                'batas_usia_pensiun' => 58,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // KASUS 2: PENSIUN BULAN DEPAN (Maret 2026)
            // Lahir: Maret 1968
            [
                'nip' => '19680320 199203 2 002',
                'nama_lengkap' => 'Siti Aminah (Pensiun Bulan Depan)',
                'tanggal_lahir' => '1968-03-20',
                'jabatan' => 'Perawat Penyelia',
                'unit_kerja' => 'Puskesmas Batang I',
                'batas_usia_pensiun' => 58,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // KASUS 3: PENSIUN AKHIR TAHUN (Desember 2026)
            [
                'nip' => '19681201 199501 1 003',
                'nama_lengkap' => 'Ahmad Dahlan (Pensiun Akhir Tahun)',
                'tanggal_lahir' => '1968-12-01',
                'jabatan' => 'Kasubbag TU',
                'unit_kerja' => 'Dinkes',
                'batas_usia_pensiun' => 58,
                'created_at' => now(), 'updated_at' => now(),
            ],

            // KASUS 4: MASIH MUDA (Lahir 1995)
            [
                'nip' => '19950817 202001 2 010',
                'nama_lengkap' => 'Rina Wati (Masih Muda)',
                'tanggal_lahir' => '1995-08-17',
                'jabatan' => 'Bidan Pelaksana',
                'unit_kerja' => 'Puskesmas Limpung',
                'batas_usia_pensiun' => 58,
                'created_at' => now(), 'updated_at' => now(),
            ],
            
            // KASUS 5: DOKTER (Batas Usia 60)
            // Lahir 1968 (Umur 58) -> Belum pensiun karena batasnya 60
            [
                'nip' => '19680510 200501 1 005',
                'nama_lengkap' => 'dr. Haryanto (Dokter - Belum Pensiun)',
                'tanggal_lahir' => '1968-05-10',
                'jabatan' => 'Dokter Umum',
                'unit_kerja' => 'Puskesmas Bandar',
                'batas_usia_pensiun' => 60,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ];

        DB::table('pegawais')->insert($data);
    }
}