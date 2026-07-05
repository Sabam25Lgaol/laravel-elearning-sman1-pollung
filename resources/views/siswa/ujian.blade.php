@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="text-info mb-1 fw-bold"><i class="fas fa-laptop-code me-2"></i>Daftar Ujian / Quiz: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Perhatikan jadwal dan durasi sebelum menekan tombol mulai.</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm rounded-3 border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm rounded-3 border-0">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-list-alt me-1 text-info"></i> Daftar Ujian yang Tersedia
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3" style="min-width: 200px;">Judul Ujian</th>
                                <th class="py-3" style="min-width: 220px;">Jadwal Pelaksanaan</th>
                                <th class="py-3 text-center" style="min-width: 100px;">Durasi</th>
                                <th class="py-3 text-center" style="min-width: 130px;">Status / Nilai</th>
                                <th class="py-3 text-center" style="min-width: 170px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sekarang = \Carbon\Carbon::now(); @endphp
                            @forelse($ujians as $u)
                            <tr>
                                <td class="px-4">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-info fs-6 text-start" data-bs-toggle="modal" data-bs-target="#detailUjian{{ $u->id }}">
                                        {{ $u->judul_ujian }}
                                    </button>
                                </td>

                                <td class="small">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-success fw-bold"><i class="fas fa-play-circle me-1"></i>Mulai: <span class="text-dark fw-normal">{{ \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y, H:i') }}</span></span>
                                        <span class="text-danger fw-bold"><i class="fas fa-stop-circle me-1"></i>Tutup: <span class="text-dark fw-normal">{{ \Carbon\Carbon::parse($u->waktu_selesai)->format('d M Y, H:i') }}</span></span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>{{ $u->durasi }} Menit</span>
                                </td>

                                <td class="text-center">
                                    @if(isset($hasil[$u->id]))
                                        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">{{ round($hasil[$u->id]->nilai) }} / 100</span>
                                    @elseif($sekarang->lt($u->waktu_mulai))
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Belum Dimulai</span>
                                    @elseif($sekarang->gt($u->waktu_selesai))
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">Waktu Habis</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill">Siap Dikerjakan</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if(isset($hasil[$u->id]))
                                        <button type="button" class="btn btn-sm btn-outline-info fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#detailUjian{{ $u->id }}">
                                            <i class="fas fa-eye me-1"></i> Lihat Detail
                                        </button>
                                    @elseif($sekarang->lt($u->waktu_mulai))
                                        <button class="btn btn-sm btn-secondary fw-bold rounded-pill shadow-sm disabled">
                                            <i class="fas fa-hourglass-half me-1"></i> Belum Dimulai
                                        </button>
                                    @elseif($sekarang->gt($u->waktu_selesai))
                                        <button class="btn btn-sm btn-danger fw-bold rounded-pill shadow-sm disabled">
                                            <i class="fas fa-times-circle me-1"></i> Waktu Habis
                                        </button>
                                    @else
                                        <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="btn btn-sm btn-info text-white fw-bold rounded-pill shadow-sm" onclick="return confirm('Apakah Anda yakin ingin memulai ujian sekarang? Waktu akan terus berjalan!')">
                                            <i class="fas fa-play me-1"></i> Mulai Kerjakan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                    <h6 class="fw-bold">Belum Ada Jadwal Ujian</h6>
                                    <small>Guru belum menambahkan ujian untuk mata pelajaran ini.</small>
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

@foreach($ujians as $u)
<div class="modal fade" id="detailUjian{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-flask me-2"></i>{{ $u->judul_ujian }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="text-muted mb-3">{{ $u->deskripsi ?? 'Tidak ada keterangan khusus.' }}</p>

                <div class="bg-white p-3 rounded-4 mb-3 small border shadow-sm">
                    <div class="mb-2"><strong class="text-success"><i class="fas fa-calendar-check me-1"></i> Dibuka:</strong> {{ \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y, H:i') }} WIB</div>
                    <div><strong class="text-danger"><i class="fas fa-calendar-times me-1"></i> Ditutup:</strong> {{ \Carbon\Carbon::parse($u->waktu_selesai)->format('d M Y, H:i') }} WIB</div>
                </div>

                @if(isset($hasil[$u->id]))
                    <div class="alert alert-success text-center mb-0 p-3 shadow-sm border-0">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                        Sudah Dikerjakan <br>
                        <strong>Nilai Anda: <span class="fs-3 d-block mt-1 text-success">{{ round($hasil[$u->id]->nilai) }} / 100</span></strong>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-white border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
