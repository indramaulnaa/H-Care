<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cutis';

    protected $fillable = [
        'id_pegawai',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'file_permohonan',
        'file_sk_resmi',
        'status',
        'keterangan_admin',
        'alasan',
    ];

    // Relasi: Cuti ini milik siapa?
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}