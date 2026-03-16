<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Storage;

class PengajuanCutiController extends Controller
{
    // 1. Fungsi Simpan Pengajuan Cuti Baru (Puskesmas)
    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required',
            'jenis_cuti' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'file_permohonan' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file_permohonan')->store('berkas_cuti', 'public');

        PengajuanCuti::create([
            'id_pegawai' => $request->id_pegawai,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'file_permohonan' => $path,
            'status' => 'menunggu'
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    // 2. Fungsi Batalkan/Hapus Pengajuan Cuti (Puskesmas)
    public function destroy($id)
    {
        $cuti = PengajuanCuti::findOrFail($id);

        if ($cuti->status == 'menunggu') {
            if ($cuti->file_permohonan && Storage::disk('public')->exists($cuti->file_permohonan)) {
                Storage::disk('public')->delete($cuti->file_permohonan);
            }
            $cuti->delete();
            return back()->with('success', 'Pengajuan cuti berhasil dibatalkan dan dihapus.');
        }

        return back()->withErrors(['error' => 'Gagal membatalkan. Pengajuan cuti ini sudah diproses oleh Dinas Kesehatan.']);
    }

    // 3. Fungsi Kunci Dokumen / Tandai Sedang Diproses (Dinas Kesehatan)
    public function tandaiDiproses($id)
    {
        $cuti = PengajuanCuti::findOrFail($id);
        
        if ($cuti->status == 'menunggu') {
            $cuti->update(['status' => 'diproses']);
            return back()->with('success', 'Pengajuan cuti sedang Anda proses. Puskesmas tidak dapat membatalkannya lagi.');
        }
        
        return back()->withErrors(['error' => 'Gagal, status pengajuan mungkin sudah berubah.']);
    }

    // 4. Fungsi Verifikasi Akhir Cuti (Dinas Kesehatan)
    public function verifikasi(Request $request, $id)
    {
        $cuti = PengajuanCuti::findOrFail($id);
        
        // Pastikan hanya yang 'diproses' atau 'menunggu' yang bisa diverifikasi
        if (in_array($cuti->status, ['menunggu', 'diproses'])) {
            
            // JIKA DISETUJUI
            if ($request->aksi == 'setuju') {
                $request->validate(['file_sk_resmi' => 'required|mimes:pdf|max:2048']);
                $path = $request->file('file_sk_resmi')->store('sk_cuti', 'public');
                
                $cuti->update([
                    'status' => 'disetujui',
                    'file_sk_resmi' => $path,
                    'keterangan_admin' => null
                ]);
                return back()->with('success', 'Pengajuan Cuti Disetujui dan SK berhasil dikirim ke Puskesmas.');
            
            // JIKA DITOLAK
            } else {
                $cuti->update([
                    'status' => 'ditolak',
                    'keterangan_admin' => $request->catatan
                ]);
                return back()->with('success', 'Pengajuan Cuti Ditolak. Catatan telah dikirim ke Puskesmas.');
            }
        }
        return back();
    }
}