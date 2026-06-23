<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelajaran;
use App\Models\Materi; // Tambahan model untuk hitung statistik
use App\Models\Tugas;  // Tambahan model untuk hitung statistik
use App\Models\Ujian;  // Tambahan model untuk hitung statistik

class GuruController extends Controller
{
    public function dashboard()
    {
        // Ambil data guru yang sedang login saat ini
        $guru = Auth::user();

        // EAGER LOADING SUNTIKAN: Tambahkan with('dataKelas')
        // Cari mata pelajaran di mana 'guru_id'-nya sama dengan ID guru yang sedang login
        $pelajarans = Pelajaran::with('dataKelas')->where('guru_id', $guru->id)->get();

        // Hitung total pelajaran yang diajar
        $total_pelajaran = $pelajarans->count();

        // ==========================================
        // STATISTIK TAMBAHAN (Saran Audit)
        // ==========================================
        // Ambil semua ID pelajaran milik guru ini
        $pelajaran_ids = $pelajarans->pluck('id');

        // Hitung total materi, tugas, dan ujian di semua kelas yang diajar guru ini
        $total_materi = Materi::whereIn('pelajaran_id', $pelajaran_ids)->count();
        $total_tugas = Tugas::whereIn('pelajaran_id', $pelajaran_ids)->count();
        $total_ujian = Ujian::whereIn('pelajaran_id', $pelajaran_ids)->count();

        // Kirim semua data ini ke tampilan (view) guru.dashboard
        return view('guru.dashboard', compact(
            'guru', 'pelajarans', 'total_pelajaran',
            'total_materi', 'total_tugas', 'total_ujian'
        ));
    }
}
