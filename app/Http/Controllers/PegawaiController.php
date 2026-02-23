<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawais,nip',
            'nama_lengkap' => 'required',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required',
            'batas_usia_pensiun' => 'required|integer',
            // unit_kerja opsional (kalau Dinkes bisa pilih, kalau Puskesmas otomatis)
        ]);

        // Logika Unit Kerja: Jika ada inputan (dari Dinkes) pakai itu, jika tidak (Puskesmas) ambil dari Auth
        $unitKerja = $request->unit_kerja ?? auth()->user()->nama_unit;

        Pegawai::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jabatan' => $request->jabatan,
            'batas_usia_pensiun' => $request->batas_usia_pensiun,
            'unit_kerja' => $unitKerja,
        ]);

        return back()->with('success', 'Data Pegawai berhasil ditambahkan!');
    }

    // Tambahkan 2 fungsi baru ini di bawah fungsi store()

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:pegawais,nip,'.$id,
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            'tanggal_lahir' => 'required|date',
            'batas_usia_pensiun' => 'required',
        ]);

        // Cek apakah ada request unit_kerja baru (untuk mutasi pegawai)
        $dataToUpdate = [
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'batas_usia_pensiun' => $request->batas_usia_pensiun,
        ];

        if ($request->has('unit_kerja')) {
            $dataToUpdate['unit_kerja'] = $request->unit_kerja;
        }

        $pegawai->update($dataToUpdate);

        return back()->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete(); // Hapus data
        return back()->with('success', 'Data pegawai berhasil dihapus.');
    }
}