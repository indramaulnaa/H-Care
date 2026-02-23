<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';

    // TAMBAHKAN 'is_pensiun_open' DI SINI AGAR BISA DISIMPAN
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'tanggal_lahir',
        'jabatan',
        'unit_kerja',
        'batas_usia_pensiun',
        'is_pensiun_open', // <--- PENTING: Tambahkan ini!
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_pensiun_open' => 'boolean', // Opsional: Agar dibaca sebagai true/false
    ];

    // Relasi
    public function berkas_pensiun()
    {
        return $this->hasOne(BerkasPensiun::class, 'id_pegawai');
    }

    public function riwayat_cuti()
    {
        return $this->hasMany(PengajuanCuti::class, 'id_pegawai');
    }
}