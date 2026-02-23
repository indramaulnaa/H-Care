<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\BerkasPensiun;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ==========================================
    // BAGIAN 1: ADMIN DINAS KESEHATAN
    // ==========================================

    // 1. Dashboard Utama Dinkes (Statistik)
    public function indexDinkes()
    {
        $tahunIni = Carbon::now()->year;

        // Hitung Statistik
        $totalPegawai = Pegawai::count();
        $totalPensiunTahunIni = Pegawai::whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [$tahunIni])->count();
        $cutiPending = PengajuanCuti::where('status', 'menunggu')->count();
        $berkasPending = BerkasPensiun::where('status', 'menunggu')->count();

        return view('dashboard.dinkes', compact('totalPegawai', 'totalPensiunTahunIni', 'cutiPending', 'berkasPending'));
    }

    // 2. Halaman Verifikasi Cuti (Dinkes)
    public function pageCuti()
    {
        $dataCuti = PengajuanCuti::with('pegawai')
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->latest()
            ->get();

        return view('dinkes.cuti', compact('dataCuti'));
    }

    // 3. Halaman Monitoring Pensiun (Dinkes)
    // 3. Halaman E-Pensiun (Monitoring & Filter)
    public function pagePensiun(Request $request)
    {
        $filterTahun = $request->input('tahun', Carbon::now()->year);
        $filterBulan = $request->input('bulan');
        $filterUnit  = $request->input('unit');
        $search      = $request->input('search'); // <--- 1. Tangkap Input Cari

        $query = Pegawai::query();

        // Filter Tahun
        $query->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [$filterTahun]);

        // Filter Bulan
        if ($filterBulan) {
            $query->whereRaw('MONTH(tanggal_lahir) = ?', [$filterBulan]);
        }

        // Filter Unit
        if ($filterUnit) {
            $query->where('unit_kerja', $filterUnit);
        }

        // 2. Logic Pencarian (Nama atau NIP)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $dataPensiun = $query->with('berkas_pensiun')
            ->orderByRaw('MONTH(tanggal_lahir) ASC')
            ->get();

        // Statistik (Logic tetap sama)
        $stats = [
            'total' => $dataPensiun->count(),
            'belum_upload' => $dataPensiun->where('berkas_pensiun', null)->count(),
            'menunggu' => $dataPensiun->where('berkas_pensiun.status', 'menunggu')->count(),
            'lengkap' => $dataPensiun->where('berkas_pensiun.status', 'disetujui')->count(),
        ];

        $pensiunBulanIniRealtime = Pegawai::whereRaw('MONTH(tanggal_lahir) = ?', [Carbon::now()->month])
            ->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [Carbon::now()->year])
            ->get();

        $listUnitKerja = Pegawai::select('unit_kerja')->distinct()->orderBy('unit_kerja')->pluck('unit_kerja');

        // Jangan lupa kirim $search ke view agar input text tidak hilang
        return view('dinkes.pensiun', compact(
            'dataPensiun', 'stats', 'pensiunBulanIniRealtime', 'listUnitKerja',
            'filterTahun', 'filterBulan', 'filterUnit', 'search'
        ));
    }

    // 3. Fungsi Baru: Membuka Akses Upload (Gatekeeper)
    public function bukaAksesPensiun($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update(['is_pensiun_open' => 1]); // Buka Gembok (Set jadi 1)
        
        return back()->with('success', 'Akses upload dokumen dibuka untuk pegawai ini.');
    }

    // 4. Halaman Data Pegawai (Master Data Se-Kabupaten)
    public function pageDataPegawaiDinkes(Request $request)
    {
        // Ambil Input Filter
        $search = $request->input('search');
        $filterUnit = $request->input('unit');

        // Query Dasar
        $query = Pegawai::query();

        // 1. Filter Unit Kerja
        if ($filterUnit) {
            $query->where('unit_kerja', $filterUnit);
        }

        // 2. Fitur Pencarian (Nama / NIP)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan Pagination (agar halaman tidak berat jika data ribuan)
        $semuaPegawai = $query->orderBy('unit_kerja', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->paginate(20); // Menampilkan 20 data per halaman

        // List Unit untuk Dropdown
        $listUnitKerja = Pegawai::select('unit_kerja')->distinct()->orderBy('unit_kerja')->pluck('unit_kerja');

        return view('dinkes.pegawai', compact('semuaPegawai', 'listUnitKerja', 'search', 'filterUnit'));
    }

    // ==========================================
    // BAGIAN 2: ADMIN PUSKESMAS
    // ==========================================

    // 1. Dashboard Utama Puskesmas
    public function indexPuskesmas()
    {
        $unitKerja = Auth::user()->nama_unit;
        
        // Statistik
        $totalPegawai = Pegawai::where('unit_kerja', $unitKerja)->count();
        
        $cutiSaya = PengajuanCuti::whereHas('pegawai', function($q) use ($unitKerja) {
            $q->where('unit_kerja', $unitKerja);
        })->count();
        
        $pensiunTahunIni = Pegawai::where('unit_kerja', $unitKerja)
            ->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [Carbon::now()->year])
            ->count();

        return view('dashboard.puskesmas', compact('totalPegawai', 'cutiSaya', 'pensiunTahunIni'));
    }

    // 2. Halaman Pengajuan Cuti (Puskesmas)
    public function pageCutiPuskesmas()
    {
        $unitKerja = Auth::user()->nama_unit;
        
        // List Pegawai untuk Dropdown Form
        $semuaPegawai = Pegawai::where('unit_kerja', $unitKerja)->get();
        
        // Riwayat Cuti
        $riwayatCuti = PengajuanCuti::whereHas('pegawai', function($q) use ($unitKerja) {
                $q->where('unit_kerja', $unitKerja);
            })->with('pegawai')->latest()->get();

        return view('puskesmas.cuti', compact('semuaPegawai', 'riwayatCuti'));
    }

    // 3. Halaman Data Pegawai (CRUD Master Data) - FITUR BARU
    public function pageDataPegawai()
    {
        $unitKerja = Auth::user()->nama_unit;
        
        // Ambil semua pegawai di unit ini (untuk diedit/dihapus)
        $semuaPegawai = Pegawai::where('unit_kerja', $unitKerja)
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('puskesmas.pegawai', compact('semuaPegawai'));
    }

    // 4. Halaman E-Pensiun (Monitoring 1 Tahun) - FITUR BARU
    // 4. Halaman E-Pensiun Puskesmas (Monitoring, Filter & Upload)
    public function pagePensiunPuskesmas(Request $request)
    {
        $unitKerja = Auth::user()->nama_unit;

        // 1. Ambil Input Filter (Default Tahun Ini)
        $filterTahun = $request->input('tahun', Carbon::now()->year);
        $filterBulan = $request->input('bulan');

        // 2. Query Utama: Pegawai di Unit Ini
        $query = Pegawai::where('unit_kerja', $unitKerja);

        // Filter Tahun Pensiun
        $query->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [$filterTahun]);

        // Filter Bulan (Jika dipilih)
        if ($filterBulan) {
            $query->whereRaw('MONTH(tanggal_lahir) = ?', [$filterBulan]);
        }

        $dataPensiun = $query->with('berkas_pensiun')
            ->orderByRaw('MONTH(tanggal_lahir) ASC')
            ->get();

        // 3. Hitung Statistik (Sesuai Desain)
        $stats = [
            'total' => $dataPensiun->count(),
            'belum_upload' => $dataPensiun->where('berkas_pensiun', null)->count(),
            'menunggu' => $dataPensiun->where('berkas_pensiun.status', 'menunggu')->count(),
            'lengkap' => $dataPensiun->where('berkas_pensiun.status', 'disetujui')->count(),
        ];

        // 4. Data Yellow Box (Peringatan Pensiun Bulan Ini - Realtime)
        $pensiunBulanIniRealtime = Pegawai::where('unit_kerja', $unitKerja)
            ->whereRaw('MONTH(tanggal_lahir) = ?', [Carbon::now()->month])
            ->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [Carbon::now()->year])
            ->get();

        return view('puskesmas.pensiun', compact(
            'dataPensiun', 'stats', 'pensiunBulanIniRealtime', 'filterTahun', 'filterBulan'
        ));
    }
}