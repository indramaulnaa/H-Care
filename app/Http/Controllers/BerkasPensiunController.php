<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerkasPensiun;

class BerkasPensiunController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_pegawai' => 'required|exists:pegawais,id',
            'file_sk_cpns' => 'required|mimes:pdf|max:2048', // Maks 2MB
            'file_sk_pangkat' => 'required|mimes:pdf|max:2048',
            'file_karpeg' => 'required|mimes:pdf|max:2048',
        ]);

        // 2. Proses Upload File
        // Kita simpan di folder: storage/app/public/berkas_pensiun
        $pathCPNS = $request->file('file_sk_cpns')->store('berkas_pensiun', 'public');
        $pathPangkat = $request->file('file_sk_pangkat')->store('berkas_pensiun', 'public');
        $pathKarpeg = $request->file('file_karpeg')->store('berkas_pensiun', 'public');

        // 3. Simpan ke Database
        // Cek dulu apakah sudah pernah upload (Update) atau belum (Create)
        BerkasPensiun::updateOrCreate(
            ['id_pegawai' => $request->id_pegawai], // Kunci pencarian
            [
                'file_sk_cpns' => $pathCPNS,
                'file_sk_pangkat' => $pathPangkat,
                'file_karpeg' => $pathKarpeg,
                'status' => 'menunggu', // Reset status jadi menunggu
            ]
        );

        return back()->with('success', 'Berkas berhasil dikirim ke Dinas Kesehatan!');
    }

    // Fungsi untuk Admin Dinkes memverifikasi berkas
    public function verifikasi(Request $request, $id)
    {
        $berkas = BerkasPensiun::findOrFail($id);
        
        if ($request->aksi == 'setuju') {
            $berkas->update(['status' => 'disetujui']);
            return back()->with('success', 'Berkas berhasil disetujui! Pegawai akan menerima notifikasi.');
        } 
        elseif ($request->aksi == 'tolak') {
            $berkas->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan // Alasan penolakan
            ]);
            return back()->with('error', 'Berkas ditolak. Puskesmas harus upload ulang.');
        }
    }
}