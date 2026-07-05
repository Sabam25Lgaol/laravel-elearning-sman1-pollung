@extends('layouts.app')

@section('content')
@php
    $mode = request('mode');
    $modeLabels = [
        'materi' => ['label' => 'Materi Pembelajaran', 'icon' => 'fa-book-open', 'desc' => 'Pilih mata pelajaran di bawah ini untuk membuka materi.'],
        'tugas' => ['label' => 'Tugas Siswa', 'icon' => 'fa-clipboard-list', 'desc' => 'Pilih mata pelajaran di bawah ini untuk melihat dan mengumpulkan tugas.'],
        'ujian' => ['label' => 'Ujian Online', 'icon' => 'fa-laptop-code', 'desc' => 'Pilih mata pelajaran di bawah ini untuk melihat jadwal ujian.'],
        'absensi' => ['label' => 'Riwayat Absensi', 'icon' => 'fa-user-check', 'desc' => 'Pilih mata pelajaran di bawah ini untuk melihat riwayat absensi.'],
    ];
    $modeInfo = $modeLabels[$mode] ?? null;

    // Kumpulan warna Gradient Ala Google Classroom (Hanya dipakai saat di halaman Dashboard Utama)
    $classGradients = [
        'linear-gradient(135deg, #2563eb 0%, #3b82f6 100%)', // Biru
        'linear-gradient(135deg, #059669 0%, #10b981 100%)', // Hijau
        'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)', // Merah
        'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%)', // Ungu
        'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)', // Orange
        'linear-gradient(135deg, #475569 0%, #64748b 100%)'  // Abu-abu Slate
    ];
@endphp

<div class="row guru-ui">

    @if(!$mode)
    {{-- ==========================================================
         BAGIAN INI HANYA MUNCUL DI DASHBOARD UTAMA (TIDAK ADA MODE)
         ========================================================== --}}

    <!-- Hero Section -->
    <div class="col-md-12 mb-4">
        <div class="card guru-hero shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="text-info fw-bold mb-2">Dashboard Siswa</h4>
                <p class="text-muted mb-3">Selamat datang, <b>{{ Auth::user()->name }}</b>. Pilih mata pelajaranmu, lalu lanjutkan materi, tugas, atau ujian dari sini.</p>
                <span class="badge bg-info px-3 py-2 rounded-pill shadow-sm">Status: Siswa</span>
            </div>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="col-6 col-md-4 mb-4">
        <div class="card guru-stat-card stat-amber shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Tugas Belum Selesai</div>
                <h3 class="fw-bold text-dark mb-0">{{ $tugasCount ?? 0 }} <small class="fs-6 text-muted fw-normal">Tugas</small></h3>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 mb-4">
        <div class="card guru-stat-card stat-emerald shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Ujian Akan Datang</div>
                <h3 class="fw-bold text-dark mb-0">{{ $ujianCount ?? 0 }} <small class="fs-6 text-muted fw-normal">Ujian</small></h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 mb-4">
        <div class="card guru-stat-card stat-cyan shadow-sm border-0 h-100">
            <div class="card-body py-3">
                <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Total Materi</div>
                <h3 class="fw-bold text-dark mb-0">{{ $materiCount ?? 0 }} <small class="fs-6 text-muted fw-normal">Materi</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-2 mb-3">
        <h5 class="fw-bold text-dark mb-1">
            <i class="fas fa-layer-group text-info me-2"></i>Daftar Mata Pelajaran
        </h5>
        <p class="text-muted small mb-0">Semua kelas yang kamu ikuti tampil di sini.</p>
    </div>

    @else
    {{-- ==========================================================
         BAGIAN INI MUNCUL JIKA MASUK DARI SIDEBAR (MODE AKTIF)
         ========================================================== --}}

    <!-- Header saat mode aktif (dari sidebar), disamakan dengan gaya "Pilih Kelas" milik guru -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="text-info mb-1 fw-bold">
                        <i class="fas fa-folder-open me-2"></i>Pilih Kelas: {{ $modeInfo['label'] }}
                    </h4>
                    <p class="mb-0 text-muted">
                        {{ $modeInfo['desc'] }}
                    </p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ==========================================================
         KONTEN: TABEL (MODE AKTIF DARI SIDEBAR) ATAU KARTU (DASHBOARD UTAMA)
         ========================================================== --}}
    @if($mode && $modeInfo)
        {{-- TAMPILAN JIKA DARI SIDEBAR: TABEL RAPI SEPERTI HALAMAN GURU --}}
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white border-top border-info border-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="px-4 py-3 text-center" style="min-width: 60px;">No</th>
                                    <th class="py-3" style="min-width: 250px;">Mata Pelajaran</th>
                                    <th class="py-3" style="min-width: 200px;">Guru Pengajar</th>
                                    <th class="py-3 text-center" style="min-width: 180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pelajarans ?? [] as $index => $p)
                                <tr>
                                    <td class="px-4 text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td><span class="fw-bold text-dark fs-6">{{ $p->nama_pelajaran }}</span></td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold border border-info border-opacity-25">
                                            <i class="fas fa-user-tie me-1"></i> {{ $p->guru->name ?? 'Belum ada guru' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('siswa.' . $mode, $p->id) }}" class="btn btn-outline-info btn-sm fw-bold rounded-pill shadow-sm transition-all guru-action-button">
                                            Buka {{ ucfirst($mode) }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x opacity-25 mb-3"></i>
                                        <h6 class="fw-bold">Belum Ada Pelajaran</h6>
                                        <small>Kamu belum terdaftar di kelas mana pun.</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- TAMPILAN DASHBOARD UTAMA: KOTAK WARNA-WARNI ALA CLASSROOM --}}
        <div class="row g-4 mb-4">
            @forelse($pelajarans ?? [] as $index => $p)
                @php
                    $bgStyle = $classGradients[$index % count($classGradients)];
                    $guruName = $p->guru->name ?? 'Belum ada guru';
                    $initial = strtoupper(substr($guruName, 0, 1));
                @endphp
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100 rounded-4 card-kelas-guru" style="background-color: #ffffff; position: relative; overflow: hidden; border-left: none !important;">
                        <div class="p-4 text-white position-relative" style="background: {{ $bgStyle }}; height: 120px;">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h4 class="fw-bold mb-0 text-truncate" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.15); max-width: 60%;" title="{{ $p->nama_pelajaran }}">
                                    {{ $p->nama_pelajaran }}
                                </h4>
                                <div class="small fw-bold text-truncate text-end text-white" style="max-width: 40%; text-shadow: 1px 1px 2px rgba(0,0,0,0.15);" title="{{ $guruName }}">
                                    <i class="fas fa-user-tie me-1"></i> {{ $guruName }}
                                </div>
                            </div>
                            @if($p->deskripsi)
                            <p class="small mt-2 mb-0 text-truncate opacity-75" style="max-width: 80%;">
                                {{ \Illuminate\Support\Str::limit($p->deskripsi, 40) }}
                            </p>
                            @endif
                        </div>
                        <div class="position-absolute shadow-sm d-flex justify-content-center align-items-center bg-white"
                            style="width: 55px; height: 55px; border-radius: 50%; border: 3px solid #ffffff; top: 90px; right: 20px; z-index: 2;">
                            <span class="fs-5 fw-bold" style="color: #475569;">{{ $initial }}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="mt-4 mb-2"></div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('siswa.materi', $p->id) }}" class="text-decoration-none">
                                        <div class="border rounded-3 py-2 px-1 text-center transition-all action-box hover-primary">
                                            <i class="fas fa-book-open d-block fs-5 mb-1 text-primary"></i>
                                            <span class="fw-bold text-primary" style="font-size: 0.75rem;">Materi</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('siswa.tugas', $p->id) }}" class="text-decoration-none">
                                        <div class="border rounded-3 py-2 px-1 text-center transition-all action-box hover-warning">
                                            <i class="fas fa-clipboard-list d-block fs-5 mb-1 text-warning"></i>
                                            <span class="fw-bold text-warning" style="font-size: 0.75rem;">Tugas</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('siswa.ujian', $p->id) }}" class="text-decoration-none">
                                        <div class="border rounded-3 py-2 px-1 text-center transition-all action-box hover-danger">
                                            <i class="fas fa-laptop-code d-block fs-5 mb-1 text-danger"></i>
                                            <span class="fw-bold text-danger" style="font-size: 0.75rem;">Ujian</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('siswa.absensi', $p->id) }}" class="text-decoration-none">
                                        <div class="border rounded-3 py-2 px-1 text-center transition-all action-box hover-success">
                                            <i class="fas fa-user-check d-block fs-5 mb-1 text-success"></i>
                                            <span class="fw-bold text-success" style="font-size: 0.75rem;">Absensi</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 text-center py-5 bg-white">
                    <div class="opacity-50 mb-3">
                        <i class="fas fa-folder-open fa-4x text-muted"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Belum Ada Pelajaran</h6>
                    <small class="text-muted">Kamu belum terdaftar di kelas mana pun.</small>
                </div>
            </div>
            @endforelse
        </div>
    @endif
</div>

<style>
    .card-kelas-guru {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-kelas-guru:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .action-box {
        background-color: #ffffff;
        border-color: #e2e8f0 !important;
    }
    .hover-primary:hover { border-color: #0284c7 !important; background-color: #f0f9ff; }
    .hover-warning:hover { border-color: #f59e0b !important; background-color: #fffbeb; }
    .hover-danger:hover { border-color: #e11d48 !important; background-color: #fff1f2; }
    .hover-success:hover { border-color: #16a34a !important; background-color: #f0fdf4; }

    .action-btn:hover {
        background-color: #0369a1 !important;
        transform: scale(1.02);
    }
</style>
@endsection
