<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;

class PengajuanCutiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required',
            'jenis_cuti' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'nullable',
            // Sesuaikan dengan prototype: PDF/JPG max 5MB (5120 KB)
            'file_permohonan' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', 
        ]);

        $path = $request->file('file_permohonan')->store('surat_cuti', 'public');

        PengajuanCuti::create([
            'id_pegawai' => $request->id_pegawai,
            'jenis_cuti' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'file_permohonan' => $path,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil dikirim!');
    }

    public function verifikasi(Request $request, $id)
    {
        $cuti = PengajuanCuti::findOrFail($id);

        if ($request->aksi == 'tolak') {
            $cuti->update(['status' => 'ditolak', 'keterangan_admin' => $request->keterangan]);
            return back()->with('error', 'Pengajuan cuti ditolak.');
        }

        if ($request->aksi == 'setuju') {
            // Validasi harus ada file SK
            $request->validate([
                'file_sk_resmi' => 'required|mimes:pdf|max:2048',
            ]);

            // Upload SK
            $path = $request->file('file_sk_resmi')->store('surat_cuti_balasan', 'public');

            $cuti->update([
                'status' => 'disetujui',
                'file_sk_resmi' => $path
            ]);

            return back()->with('success', 'Cuti disetujui & SK berhasil diupload!');
        }
    }
}