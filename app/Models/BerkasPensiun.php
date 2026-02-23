<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPensiun extends Model
{
    use HasFactory;

    protected $table = 'berkas_pensiuns';

    protected $fillable = [
        'id_pegawai',
        'file_sk_cpns',
        'file_sk_pangkat',
        'file_karpeg',
        'status',
        'catatan_admin',
    ];

    // Relasi balik: Berkas ini milik satu Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}