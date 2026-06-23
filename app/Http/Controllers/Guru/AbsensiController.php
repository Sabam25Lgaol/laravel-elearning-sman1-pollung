<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelajaran;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function pilihKelas()
    {
        $pelajarans = Pelajaran::with('dataKelas')
                        ->where('guru_id', Auth::id())
                        ->get();
        return view('guru.absensi_pilih_kelas', compact('pelajarans'));
    }

    public function index(Request $request, $pelajaran_id)
    {
        $pelajaran = Pelajaran::findOrFail($pelajaran_id);

        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak! Anda bukan pengampu kelas ini.');
        }

        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();
        $siswas = $pelajaran->siswa()->orderBy('name', 'asc')->get();

        $absensi_hari_ini = Absensi::where('pelajaran_id', $pelajaran_id)
                                   ->where('tanggal', $tanggal)
                                   ->get()
                                   ->keyBy('siswa_id');

        // ====================================================================
        // LOGIKA BARU: MENGELOMPOKKAN ABSENSI BERDASARKAN PERTEMUAN (TANGGAL)
        // ====================================================================
        $semua_absensi = Absensi::where('pelajaran_id', $pelajaran_id)->get();

        // Kelompokkan berdasarkan tanggal, lalu urutkan dari yang paling lama ke terbaru
        $absensi_per_tanggal = $semua_absensi->groupBy('tanggal')->sortKeys();

        $riwayat_pertemuan = [];
        $pertemuan_ke = 1;

        foreach ($absensi_per_tanggal as $tgl => $data_absensi) {
            $riwayat_pertemuan[$tgl] = [
                'pertemuan'   => $pertemuan_ke,
                'tanggal'     => $tgl,
                'total_hadir' => $data_absensi->where('status', 'Hadir')->count(),
                'total_izin'  => $data_absensi->where('status', 'Izin')->count(),
                'total_sakit' => $data_absensi->where('status', 'Sakit')->count(),
                'total_alfa'  => $data_absensi->where('status', 'Alfa')->count(),
                'detail'      => $data_absensi->keyBy('siswa_id') // Data untuk ditampilkan di dalam Modal
            ];
            $pertemuan_ke++;
        }

        return view('guru.absensi', compact('pelajaran', 'siswas', 'tanggal', 'absensi_hari_ini', 'riwayat_pertemuan'));
    }

    public function store(Request $request, $pelajaran_id)
    {
        $pelajaran = Pelajaran::findOrFail($pelajaran_id);

        if ($pelajaran->guru_id != Auth::id()) {
            abort(403, 'Akses Ditolak!');
        }

        $tanggal = $request->tanggal;
        $status_absensi = $request->status;

        if($status_absensi) {
            foreach ($status_absensi as $siswa_id => $status) {
                Absensi::updateOrCreate(
                    [
                        'pelajaran_id' => $pelajaran_id,
                        'siswa_id' => $siswa_id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status' => $status
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Luar biasa! Data absensi tanggal ' . date('d M Y', strtotime($tanggal)) . ' berhasil disimpan!');
    }
}
