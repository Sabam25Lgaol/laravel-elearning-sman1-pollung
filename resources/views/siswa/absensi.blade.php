@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="text-info mb-1 fw-bold"><i class="fas fa-calendar-check me-2"></i>Riwayat Absensi: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Pantau rekap kehadiranmu di mata pelajaran ini.</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold text-dark border-bottom pb-2 border-2 border-info d-inline-block">Ringkasan Kehadiran</h5>
    </div>

    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm border-0 card-stat bg-white border-bottom-5-success h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="text-success mb-2"><i class="fas fa-user-check fa-2x"></i></div>
                <h6 class="text-muted fw-bold mb-1" style="font-size: 0.85rem;">Hadir</h6>
                <h2 class="fw-bold text-dark mb-0 fs-3">{{ $rekap['Hadir'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm border-0 card-stat bg-white border-bottom-5-warning h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="text-warning mb-2"><i class="fas fa-envelope-open-text fa-2x"></i></div>
                <h6 class="text-muted fw-bold mb-1" style="font-size: 0.85rem;">Izin</h6>
                <h2 class="fw-bold text-dark mb-0 fs-3">{{ $rekap['Izin'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm border-0 card-stat bg-white border-bottom-5-info h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="text-info mb-2"><i class="fas fa-briefcase-medical fa-2x"></i></div>
                <h6 class="text-muted fw-bold mb-1" style="font-size: 0.85rem;">Sakit</h6>
                <h2 class="fw-bold text-dark mb-0 fs-3">{{ $rekap['Sakit'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-4">
        <div class="card shadow-sm border-0 card-stat bg-white border-bottom-5-danger h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="text-danger mb-2"><i class="fas fa-user-times fa-2x"></i></div>
                <h6 class="text-muted fw-bold mb-1" style="font-size: 0.85rem;">Alfa</h6>
                <h2 class="fw-bold text-dark mb-0 fs-3">{{ $rekap['Alfa'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-5">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list-alt text-info me-2"></i>Rincian per Pertemuan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 text-start py-3" style="min-width: 200px;">Tanggal Pertemuan</th>
                                <th class="py-3" style="min-width: 150px;">Status Kehadiran</th>
                                <th class="py-3" style="min-width: 180px;">Keterangan Sistem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensis as $absen)
                            <tr>
                                <td class="px-4 text-start fw-bold text-dark py-3">
                                    <i class="far fa-calendar-check text-secondary me-2"></i> {{ \Carbon\Carbon::parse($absen->tanggal)->format('d F Y') }}
                                </td>
                                <td>
                                    @if($absen->status == 'Hadir')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 fw-bold"><i class="fas fa-check me-1"></i> Hadir</span>
                                    @elseif($absen->status == 'Izin')
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning rounded-pill px-3 py-2 fw-bold"><i class="fas fa-envelope me-1"></i> Izin</span>
                                    @elseif($absen->status == 'Sakit')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2 fw-bold"><i class="fas fa-plus-square me-1"></i> Sakit</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2 fw-bold"><i class="fas fa-times me-1"></i> Alfa</span>
                                    @endif
                                </td>
                                <td class="text-muted small fst-italic">Dicatat oleh Guru</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
                                    <div class="opacity-50 mb-3">
                                        <i class="fas fa-clipboard-list fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Belum ada catatan absensi.</h6>
                                    <small>Guru belum memasukkan data kehadiran untuk Anda di pelajaran ini.</small>
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
