@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4" style="background-color: #ffffff;">
            <div class="card-body p-4 p-md-5 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-bold border border-primary border-opacity-25" style="letter-spacing: 0.5px;">
                        <i class="fas fa-calendar-alt me-1"></i> Tahun Ajaran 2024/2025
                    </span>
                    <h2 class="fw-bolder mb-2 text-dark">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                    <p class="mb-0 fs-6 text-muted">Mari lanjutkan proses belajarmu hari ini dengan penuh semangat.</p>
                </div>
                <div class="d-none d-md-block text-primary opacity-25">
                    <i class="fas fa-book-reader" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-3 mt-2 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-layer-group text-primary me-2"></i>Daftar Mata Pelajaran</h5>
    </div>

    @forelse($pelajarans as $pelajaran)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100 rounded-4 card-pelajaran" style="background-color: #ffffff;">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-book fs-4"></i>
                    </div>
                    <span class="badge bg-light text-secondary border px-2 py-1 fw-semibold">
                        <i class="fas fa-user-tie me-1"></i> {{ $pelajaran->guru->name }}
                    </span>
                </div>

                <h5 class="fw-bold text-dark mb-2">{{ $pelajaran->nama_pelajaran }}</h5>
                <p class="text-muted small line-clamp-2 mb-4" style="line-height: 1.6;">
                    {{ $pelajaran->deskripsi ?? 'Belum ada deskripsi khusus untuk mata pelajaran ini.' }}
                </p>

                <div class="row g-2 mt-auto">
                    <div class="col-6">
                        <a href="{{ route('siswa.materi', $pelajaran->id) }}" class="btn btn-light w-100 fw-semibold rounded-3 py-2 btn-menu border text-primary" style="font-size: 0.85rem;">
                            <i class="fas fa-book-open mb-1 d-block fs-5"></i> Materi
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('siswa.tugas', $pelajaran->id) }}" class="btn btn-light w-100 fw-semibold rounded-3 py-2 btn-menu border text-warning" style="font-size: 0.85rem;">
                            <i class="fas fa-clipboard-list mb-1 d-block fs-5"></i> Tugas
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('siswa.ujian', $pelajaran->id) }}" class="btn btn-light w-100 fw-semibold rounded-3 py-2 btn-menu border text-danger" style="font-size: 0.85rem;">
                            <i class="fas fa-laptop-code mb-1 d-block fs-5"></i> Ujian
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('siswa.absensi', $pelajaran->id) }}" class="btn btn-light w-100 fw-semibold rounded-3 py-2 btn-menu border text-success" style="font-size: 0.85rem;">
                            <i class="fas fa-user-check mb-1 d-block fs-5"></i> Absensi
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @empty

    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4" style="background-color: #ffffff;">
            <div class="card-body text-center py-5 my-3">
                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark">Belum Ada Pelajaran</h5>
                <p class="text-muted mb-0">Kamu belum terdaftar di mata pelajaran apa pun.<br>Silakan hubungi Guru atau Admin untuk konfirmasi kelas.</p>
            </div>
        </div>
    </div>
    @endforelse

</div>
@endsection
