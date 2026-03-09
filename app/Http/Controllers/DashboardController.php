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
    // Dashboard Utama Dinas Kesehatan
    public function indexDinkes()
    {
        $tahunIni = \Carbon\Carbon::now()->year;

        // --- A. Statistik Angka Cepat (Seluruh Unit) ---
        $totalPegawai = Pegawai::count();
        
        $cutiPending = PengajuanCuti::where('status', 'menunggu')->count();
        
        $pensiunTahunIni = Pegawai::whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) = ?', [$tahunIni])->count();

        // --- B. Data Tabel Mini (Overview Cepat) ---
        // 5 Cuti Terbaru yang BUTUH VERIFIKASI
        $cutiTerbaru = PengajuanCuti::with('pegawai')
            ->where('status', 'menunggu') // Fokus pada yang pending
            ->latest()
            ->take(5)
            ->get();

        // 5 Pegawai dengan Pensiun Terdekat di seluruh unit
        $pensiunTerdekat = Pegawai::whereRaw('(YEAR(tanggal_lahir) + batas_usia_pensiun) >= ?', [$tahunIni])
            ->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) ASC')
            ->take(5)
            ->get();

        return view('dashboard.dinkes', compact(
            'totalPegawai', 'cutiPending', 'pensiunTahunIni', 'cutiTerbaru', 'pensiunTerdekat'
        ));
    }

    // 2. Halaman Verifikasi Cuti (Dinkes)
    // Halaman Verifikasi Cuti (Dinas Kesehatan)
    public function pageCuti(Request $request)
    {
        // 1. Ambil daftar semua Unit Kerja dari tabel Pegawai untuk dropdown filter
        $listUnitKerja = \App\Models\Pegawai::select('unit_kerja')
                            ->whereNotNull('unit_kerja')
                            ->where('unit_kerja', '!=', '')
                            ->distinct()
                            ->pluck('unit_kerja');

        // 2. Tangkap input filter yang dikirim dari halaman website
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');
        $filterUnit = $request->input('unit');
        $search = $request->input('search');

        // 3. Siapkan query dasar pengajuan cuti beserta data pegawainya
        $query = \App\Models\PengajuanCuti::with('pegawai');

        // 4. Aplikasikan Filter Tanggal & Bulan (Berada di tabel Cuti)
        if ($filterBulan) {
            $query->whereMonth('tanggal_mulai', $filterBulan);
        }
        if ($filterTahun) {
            $query->whereYear('tanggal_mulai', $filterTahun);
        }

        // 5. Aplikasikan Filter Unit Kerja & Pencarian Nama/NIP (Berada di tabel Pegawai)
        if ($filterUnit || $search) {
            $query->whereHas('pegawai', function ($q) use ($filterUnit, $search) {
                if ($filterUnit) {
                    $q->where('unit_kerja', $filterUnit);
                }
                if ($search) {
                    $q->where(function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', '%' . $search . '%')
                             ->orWhere('nip', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        // 6. Ambil data yang sudah difilter (Tampilkan maksimal 15 per halaman agar rapi)
        $dataCuti = $query->latest()->paginate(15);

        // 7. Kirim semua data dan status filter kembali ke tampilan
        return view('dinkes.cuti', compact(
            'dataCuti', 'listUnitKerja', 'filterBulan', 'filterTahun', 'filterUnit', 'search'
        ));
    }

    // 3. Halaman Monitoring Pensiun (Dinkes)
    // 3. Halaman E-Pensiun (Monitoring & Filter)
    // Halaman E-Pensiun (Dinas Kesehatan)
    public function pagePensiun(Request $request)
    {
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun', date('Y'));
        $filterUnit = $request->input('unit');
        $search = $request->input('search');

        // 1. Query Dasar (Sertakan relasi berkas agar tidak lambat)
        $query = \App\Models\Pegawai::with('berkas_pensiun');

        // 2. Data Peringatan: Pensiun Bulan Ini (Seluruh Unit)
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $pensiunBulanIniRealtime = \App\Models\Pegawai::whereRaw('MONTH(DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR)) = ?', [$bulanIni])
            ->whereRaw('YEAR(DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR)) = ?', [$tahunIni])
            ->get();

        // 3. Filter Tabel Utama
        $query->whereRaw('YEAR(DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR)) = ?', [$filterTahun]);

        if ($filterBulan) {
            $query->whereRaw('MONTH(DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR)) = ?', [$filterBulan]);
        }
        if ($filterUnit) {
            $query->where('unit_kerja', $filterUnit);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nip', 'like', '%'.$search.'%');
            });
        }

        $dataPensiun = $query->get();

        // 4. Kalkulasi Statistik Pensiun
        $stats = [
            'total' => $dataPensiun->count(),
            'belum_upload' => $dataPensiun->where('berkas_pensiun', null)->count(),
            'menunggu' => $dataPensiun->where('berkas_pensiun.status', 'menunggu')->count(),
            'lengkap' => $dataPensiun->where('berkas_pensiun.status', 'disetujui')->count(),
        ];

        // 5. Ambil daftar unit kerja
        $listUnitKerja = \App\Models\Pegawai::select('unit_kerja')
            ->whereNotNull('unit_kerja')
            ->where('unit_kerja', '!=', '')
            ->distinct()
            ->pluck('unit_kerja');

        return view('dinkes.pensiun', compact(
            'dataPensiun', 'stats', 'filterBulan', 'filterTahun', 
            'filterUnit', 'search', 'listUnitKerja', 'pensiunBulanIniRealtime'
        ));
    }

    // 3. Fungsi Baru: Membuka Akses Upload (Gatekeeper)
    // Fungsi Milik Admin Dinkes (Update)
    public function bukaAksesPensiun($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update([
            'is_pensiun_open' => 1,          // Buka gembok utama
            'is_request_open_access' => 0    // Reset notifikasi permintaan
        ]); 

        return back()->with('success', 'Akses upload dokumen dibuka untuk pegawai ini.');
    }

    // 4. Halaman Data Pegawai (Master Data Se-Kabupaten)
    // Halaman Data Pegawai (Semua Unit) - Dinas Kesehatan
    public function pageDataPegawaiDinkes(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama_asc');
        $filterUnit = $request->input('unit');

        // 1. Query Dasar
        $query = \App\Models\Pegawai::query();

        // 2. Filter Pencarian (Nama & NIP)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        // 3. Filter Unit Kerja
        if ($filterUnit) {
            $query->where('unit_kerja', $filterUnit);
        }

        // 4. Sorting (Pengurutan)
        switch ($sort) {
            case 'tgl_lahir_asc':
                $query->orderBy('tanggal_lahir', 'asc');
                break;
            case 'tgl_lahir_desc':
                $query->orderBy('tanggal_lahir', 'desc');
                break;
            case 'pensiun_terdekat':
                $query->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) ASC');
                break;
            case 'pensiun_terlama':
                $query->orderByRaw('DATE_ADD(tanggal_lahir, INTERVAL batas_usia_pensiun YEAR) DESC');
                break;
            case 'nama_asc':
            default:
                $query->orderBy('nama_lengkap', 'asc');
                break;
        }

        // --- 5. LOGIKA EXPORT EXCEL (CSV) ---
        if ($request->has('export')) {
            $dataExport = $query->get();
            $fileName = 'Data_Pegawai_SeKabupaten_' . date('d-m-Y') . '.csv';
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($dataExport) {
                $file = fopen('php://output', 'w');
                // Header Kolom Excel
                fputcsv($file, ['No', 'NIP', 'Nama Pegawai', 'Jabatan', 'Unit Kerja', 'Tanggal Lahir', 'Batas Pensiun', 'Estimasi Tanggal Pensiun']);

                $no = 1;
                foreach ($dataExport as $row) {
                    $tglPensiun = \Carbon\Carbon::parse($row->tanggal_lahir)->addYears($row->batas_usia_pensiun)->format('d/m/Y');
                    fputcsv($file, [
                        $no++,
                        $row->nip,
                        $row->nama_lengkap,
                        $row->jabatan,
                        $row->unit_kerja,
                        \Carbon\Carbon::parse($row->tanggal_lahir)->format('d/m/Y'),
                        $row->batas_usia_pensiun . ' Tahun',
                        $tglPensiun
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
        // ------------------------------------

        // Eksekusi Query untuk Tampilan Pagination
        $semuaPegawai = $query->paginate(15);

        // Ambil daftar unit kerja untuk dropdown filter
        $listUnitKerja = \App\Models\Pegawai::select('unit_kerja')
            ->whereNotNull('unit_kerja')
            ->where('unit_kerja', '!=', '')
            ->distinct()
            ->pluck('unit_kerja');

        return view('dinkes.pegawai', compact('semuaPegawai', 'search', 'sort', 'filterUnit', 'listUnitKerja'));
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
    // 2. Halaman Pengajuan Cuti (Puskesmas)
    public function pageCutiPuskesmas(Request $request)
    {
        $unitKerja = Auth::user()->nama_unit;
        
        $semuaPegawai = Pegawai::where('unit_kerja', $unitKerja)->get();
        
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');
        $search = $request->input('search');

        $query = PengajuanCuti::with('pegawai')->whereHas('pegawai', function($q) use ($unitKerja, $search) {
            $q->where('unit_kerja', $unitKerja);
            if ($search) {
                $q->where(function($subQuery) use ($search) {
                    $subQuery->where('nama_lengkap', 'like', '%' . $search . '%')
                             ->orWhere('nip', 'like', '%' . $search . '%');
                });
            }
        });

        if ($filterBulan) { $query->whereMonth('tanggal_mulai', $filterBulan); }
        if ($filterTahun) { $query->whereYear('tanggal_mulai', $filterTahun); }

        // --- LOGIKA BARU: FITUR EXPORT EXCEL (CSV) ---
        if ($request->has('export')) {
            $dataExport = $query->latest()->get();
            $fileName = 'Rekap_Cuti_' . str_replace(' ', '_', $unitKerja) . '_' . date('d-m-Y') . '.csv';
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($dataExport) {
                $file = fopen('php://output', 'w');
                // Header Kolom Tabel
                fputcsv($file, ['No', 'NIP', 'Nama Pegawai', 'Jenis Cuti', 'Tanggal Mulai', 'Tanggal Selesai', 'Lama Cuti (Hari)', 'Status']);

                $no = 1;
                foreach ($dataExport as $row) {
                    $tglMulai = \Carbon\Carbon::parse($row->tanggal_mulai);
                    $tglSelesai = \Carbon\Carbon::parse($row->tanggal_selesai);
                    $hari = $tglMulai->diffInDays($tglSelesai) + 1; // +1 agar hari awal ikut terhitung

                    fputcsv($file, [
                        $no++,
                        $row->pegawai->nip,
                        $row->pegawai->nama_lengkap,
                        $row->jenis_cuti,
                        $tglMulai->format('d/m/Y'),
                        $tglSelesai->format('d/m/Y'),
                        $hari,
                        strtoupper($row->status)
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
        // ---------------------------------------------

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

    // Fungsi untuk Admin Puskesmas Meminta Akses
    public function requestBukaAksesPensiun($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update(['is_request_open_access' => 1]); // Set jadi 1 (Meminta)

        return back()->with('success', 'Permintaan buka akses berhasil dikirim. Menunggu persetujuan Admin Dinkes.');
    }
}