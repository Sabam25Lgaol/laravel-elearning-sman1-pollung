@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card guru-hero shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="text-info fw-bold mb-2">Dashboard Guru</h4>
                <p class="text-muted mb-3">Selamat datang, <b>Bapak/Ibu {{ $guru->name }}</b>. Berikut adalah ringkasan aktivitas dan kelas yang Anda ampu.</p>
                <span class="badge bg-info px-3 py-2 rounded-pill shadow-sm">Jabatan: Guru</span>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card guru-stat-card stat-cyan shadow-sm border-0 h-100">
            <div class="card-body py-3 py-md-4">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Total Kelas</div>
                <h3 class="fw-bold text-dark mb-0">{{ $total_pelajaran }} <small class="fs-6 text-muted fw-normal">Kelas</small></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card guru-stat-card stat-cyan shadow-sm border-0 h-100">
            <div class="card-body py-3 py-md-4">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Total Materi</div>
                <h3 class="fw-bold text-dark mb-0">{{ $total_materi }} <small class="fs-6 text-muted fw-normal">Materi</small></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card guru-stat-card stat-amber shadow-sm border-0 h-100">
            <div class="card-body py-3 py-md-4">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Total Tugas</div>
                <h3 class="fw-bold text-dark mb-0">{{ $total_tugas }} <small class="fs-6 text-muted fw-normal">Tugas</small></h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card guru-stat-card stat-emerald shadow-sm border-0 h-100">
            <div class="card-body py-3 py-md-4">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.75rem;">Total Ujian</div>
                <h3 class="fw-bold text-dark mb-0">{{ $total_ujian }} <small class="fs-6 text-muted fw-normal">Ujian</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-2">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-chalkboard text-info me-2"></i>Daftar Kelas Anda</h5>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="background-color: #ffffff;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3 text-center" style="min-width: 60px;">No</th>
                                <th class="py-3" style="min-width: 150px;">Kelas</th>
                                <th class="py-3" style="min-width: 200px;">Mata Pelajaran</th>
                                <th class="py-3" style="min-width: 250px;">Deskripsi</th>
                                <th class="py-3 text-center" style="min-width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelajarans as $index => $p)
                            <tr>
                                <td class="px-4 text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold border border-info border-opacity-25">
                                        <i class="fas fa-door-open me-1"></i> {{ $p->dataKelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">{{ $p->nama_pelajaran }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ \Illuminate\Support\Str::limit($p->deskripsi, 60, '...') }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('guru.pelajaran.show', $p->id) }}" class="btn btn-outline-info btn-sm fw-bold rounded-pill px-4 shadow-sm transition-all">
                                        Masuk Ruang Kelas
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="opacity-50 mb-3">
                                        <i class="fas fa-chalkboard-teacher fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Belum Ada Jadwal Mengajar</h6>
                                    <small>Anda belum ditugaskan untuk mengajar oleh Admin.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
