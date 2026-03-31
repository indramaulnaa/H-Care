<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerkasPensiun;
use App\Models\Pegawai;

class BerkasPensiunController extends Controller
{
    // Fungsi untuk PUSKESMAS mengupload berkas
    public function store(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->id_pegawai);
        $berkas = BerkasPensiun::where('id_pegawai', $pegawai->id)->first();

        // JIKA TAHAP 1 (Belum pernah upload ATAU Ditolak Tahap 1)
        if (!$berkas || $berkas->status == 'ditolak_tahap_1') {
            $request->validate([
                'file_sk_cpns' => 'required|mimes:pdf|max:2048',
                'file_sk_pangkat' => 'required|mimes:pdf|max:2048',
            ]);

            $path1 = $request->file('file_sk_cpns')->store('berkas_pensiun', 'public');
            $path2 = $request->file('file_sk_pangkat')->store('berkas_pensiun', 'public');

            if (!$berkas) {
                BerkasPensiun::create([
                    'id_pegawai' => $pegawai->id,
                    'file_sk_cpns' => $path1,
                    'file_sk_pangkat' => $path2,
                    'status' => 'menunggu_tahap_1'
                ]);
            } else {
                $berkas->update([
                    'file_sk_cpns' => $path1,
                    'file_sk_pangkat' => $path2,
                    'status' => 'menunggu_tahap_1',
                    'catatan_dinkes' => null // Kosongkan catatan saat re-upload
                ]);
            }
            return back()->with('success', 'Tahap 1 Berhasil: SK CPNS dan SK Pangkat terkirim. Menunggu verifikasi Dinkes.');
        } 
        // JIKA TAHAP 2 (Lulus Tahap 1 ATAU Ditolak Tahap 2)
        elseif ($berkas->status == 'lulus_tahap_1' || $berkas->status == 'ditolak_tahap_2') {
            $request->validate([
                'file_karpeg' => 'required|mimes:pdf|max:2048',
            ]);

            $path3 = $request->file('file_karpeg')->store('berkas_pensiun', 'public');
            
            $berkas->update([
                'file_karpeg' => $path3,
                'status' => 'menunggu_tahap_2',
                'catatan_dinkes' => null // Kosongkan catatan saat re-upload
            ]);
            return back()->with('success', 'Tahap 2 Berhasil: Kartu Pegawai (Karpeg) terkirim. Menunggu verifikasi akhir.');
        }

        return back()->withErrors(['error' => 'Akses upload tidak valid.']);
    }

    // Fungsi untuk DINKES memverifikasi berkas
    public function verifikasi(Request $request, $id)
    {
        $berkas = BerkasPensiun::findOrFail($id);
        $aksi = $request->aksi; // isinya: 'setuju' atau 'tolak'

        // VERIFIKASI TAHAP 1
        if ($berkas->status == 'menunggu_tahap_1') {
            if ($aksi == 'setuju') {
                $berkas->update(['status' => 'lulus_tahap_1', 'catatan_dinkes' => null]);
                return back()->with('success', 'Tahap 1 Disetujui. Puskesmas kini bisa mengupload Karpeg.');
            } else {
                // Simpan alasan penolakan ke kolom catatan_dinkes
                $berkas->update(['status' => 'ditolak_tahap_1', 'catatan_dinkes' => $request->catatan]);
                return back()->with('success', 'Tahap 1 Ditolak. Dikembalikan ke Puskesmas untuk revisi.');
            }
        } 
        // VERIFIKASI TAHAP 2
        elseif ($berkas->status == 'menunggu_tahap_2') {
            if ($aksi == 'setuju') {
                $berkas->update(['status' => 'disetujui', 'catatan_dinkes' => null]);
                return back()->with('success', 'Tahap 2 Disetujui. Proses pensiun SELESAI sepenuhnya!');
            } else {
                // Simpan alasan penolakan ke kolom catatan_dinkes
                $berkas->update(['status' => 'ditolak_tahap_2', 'catatan_dinkes' => $request->catatan]);
                return back()->with('success', 'Tahap 2 Ditolak. Dikembalikan ke Puskesmas untuk revisi Karpeg.');
            }
        }

        return back();
    }
}