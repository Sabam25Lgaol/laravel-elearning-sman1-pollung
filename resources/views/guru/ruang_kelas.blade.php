@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Identitas Kelas -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-info text-dark mb-2 fs-6 px-3 py-2">Kelas: {{ $pelajaran->kelas ?? 'Belum Diatur' }}</span>
                    <h2 class="text-primary fw-bold mb-1">{{ $pelajaran->nama_pelajaran }}</h2>
                    <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> {{ $pelajaran->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Menu Utama Ruang Kelas -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('guru.materi', $pelajaran->id) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center h-100 hover-card py-4" style="transition: 0.3s; border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-book-open fa-4x text-info mb-3"></i>
                    <h4 class="fw-bold text-dark">Materi</h4>
                    <p class="text-muted small mb-0">Upload modul & dokumen</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('guru.tugas', $pelajaran->id) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center h-100 hover-card py-4" style="transition: 0.3s; border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-tasks fa-4x text-warning mb-3"></i>
                    <h4 class="fw-bold text-dark">Tugas</h4>
                    <p class="text-muted small mb-0">Berikan tugas & input nilai</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('guru.absensi', $pelajaran->id) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center h-100 hover-card py-4" style="transition: 0.3s; border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-4x text-danger mb-3"></i>
                    <h4 class="fw-bold text-dark">Absensi</h4>
                    <p class="text-muted small mb-0">Catat kehadiran harian</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('guru.ujian', $pelajaran->id) }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center h-100 hover-card py-4" style="transition: 0.3s; border-radius: 15px;">
                <div class="card-body">
                    <i class="fas fa-flask fa-4x mb-3" style="color: #6f42c1;"></i>
                    <h4 class="fw-bold text-dark">Ujian</h4>
                    <p class="text-muted small mb-0">Kelola soal & pantau ujian</p>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    .hover-card { background-color: #ffffff; }
    .hover-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        border-bottom: 5px solid #0d6efd !important;
        background-color: #f8f9fa;
    }
</style>
@endsection
