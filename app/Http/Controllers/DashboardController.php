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
    // 1. Dashboard Utama Puskesmas
    public function indexPuskesmas()
    {
        $unitKerja = Auth::user()->nama_unit;
        $tahunIni = \Carbon\Carbon::now()->year;
        
        // --- A. Statistik Angka Cepat ---
        $totalPegawai = Pegawai::where('unit_kerja', $unitKerja)->count();
        
        $cutiSaya = PengajuanCuti::whereHas('pegawai', function($q) use ($unitKerja) {
            $q->where('unit_kerja', $unitKerja);
        })->count();
        
        $pensiunTahunIni = Pegawai::where('unit_kerja', $unitKerja)
            ->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [$tahunIni])
            ->count();

        // --- B. Data Tabel Mini (Overview 5 Terbaru) ---
        // 5 Riwayat Cuti Terakhir
        $cutiTerbaru = PengajuanCuti::whereHas('pegawai', function($q) use ($unitKerja) {
                $q->where('unit_kerja', $unitKerja);
            })->with('pegawai')->latest()->take(5)->get();

        // 5 Pegawai dengan Pensiun Paling Dekat
        $pensiunTerdekat = Pegawai::where('unit_kerja', $unitKerja)
            ->whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) >= ?', [$tahunIni])
            ->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) ASC')
            ->take(5)->get();

        return view('dashboard.puskesmas', compact(
            'totalPegawai', 'cutiSaya', 'pensiunTahunIni', 'cutiTerbaru', 'pensiunTerdekat'
        ));
    }

    // 2. Halaman Pengajuan Cuti (Puskesmas)
    // 2. Halaman Pengajuan Cuti (Puskesmas)
    public function pageCutiPuskesmas(Request $request)
    {
        $unitKerja = Auth::user()->nama_unit;
        
        // List Pegawai untuk Dropdown Form (Tidak difilter agar form tambah tetap bisa cari semua)
        $semuaPegawai = Pegawai::where('unit_kerja', $unitKerja)->get();
        
        // Ambil Input Filter
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');
        $search = $request->input('search');

        // Query Riwayat Cuti dengan Filter
        $query = PengajuanCuti::with('pegawai')->whereHas('pegawai', function($q) use ($unitKerja, $search) {
            $q->where('unit_kerja', $unitKerja);
            
            // Logika Pencarian Nama/NIP
            if ($search) {
                $q->where(function($subQuery) use ($search) {
                    $subQuery->where('nama_lengkap', 'like', '%' . $search . '%')
                             ->orWhere('nip', 'like', '%' . $search . '%');
                });
            }
        });

        // Logika Filter Bulan
        if ($filterBulan) {
            $query->whereMonth('tanggal_mulai', $filterBulan);
        }

        // Logika Filter Tahun
        if ($filterTahun) {
            $query->whereYear('tanggal_mulai', $filterTahun);
        }

        $riwayatCuti = $query->latest()->get();

        return view('puskesmas.cuti', compact('semuaPegawai', 'riwayatCuti', 'filterBulan', 'filterTahun', 'search'));
    }

    // 3. Halaman Data Pegawai (CRUD Master Data) - FITUR BARU
    // 3. Halaman Data Pegawai (Puskesmas) - Update Search & Sort
    public function pageDataPegawai(Request $request)
    {
        $unitKerja = Auth::user()->nama_unit;
        
        // Ambil input pencarian dan pengurutan
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama_asc'); // Default urut nama A-Z

        // Query dasar (Hanya ambil pegawai di unit puskesmas yang login)
        $query = Pegawai::where('unit_kerja', $unitKerja);

        // 1. Fitur Pencarian (Nama atau NIP)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        // 2. Fitur Urutkan Berdasarkan (Sorting)
        if ($sort == 'tgl_lahir_asc') {
            $query->orderBy('tanggal_lahir', 'asc'); // Paling Tua
        } elseif ($sort == 'tgl_lahir_desc') {
            $query->orderBy('tanggal_lahir', 'desc'); // Paling Muda
        } elseif ($sort == 'pensiun_terdekat') {
            // Rumus SQL: Tanggal Lahir + Batas Usia Pensiun
            $query->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) ASC');
        } elseif ($sort == 'pensiun_terlama') {
            $query->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) DESC');
        } else {
            $query->orderBy('nama_lengkap', 'asc'); // Default A-Z
        }

        // Gunakan pagination agar rapi (20 data per halaman)
        $semuaPegawai = $query->paginate(20);

        return view('puskesmas.pegawai', compact('semuaPegawai', 'search', 'sort'));
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