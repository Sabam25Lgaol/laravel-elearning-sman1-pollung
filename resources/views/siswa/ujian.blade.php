@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-4 border-purple">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold text-purple"><i class="fas fa-flask me-2"></i>Daftar Ujian / Quiz: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Perhatikan jadwal dan durasi sebelum menekan tombol mulai.</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold fs-5 text-center py-3 border-success border-2 shadow-sm rounded-4">
            🎉 {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm border-0 border-start border-danger border-5">
            ❌ {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="col-md-12">
        <div class="row">
            @forelse($ujians as $u)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-header bg-purple d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold fs-5">{{ $u->judul_ujian }}</span>
                        <span class="badge bg-light text-dark shadow-sm"><i class="fas fa-clock text-warning me-1"></i> {{ $u->durasi }} Menit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">{{ $u->deskripsi ?? 'Tidak ada keterangan khusus.' }}</p>

                        <div class="bg-light p-3 rounded mb-3 small border">
                            <div class="mb-2"><strong class="text-success"><i class="fas fa-calendar-check me-1"></i> Dibuka:</strong> {{ \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y, H:i') }} WIB</div>
                            <div><strong class="text-danger"><i class="fas fa-calendar-times me-1"></i> Ditutup:</strong> {{ \Carbon\Carbon::parse($u->waktu_selesai)->format('d M Y, H:i') }} WIB</div>
                        </div>

                        <hr class="text-muted opacity-25">

                        @if(isset($hasil[$u->id]))
                            <div class="alert alert-success text-center mb-0 p-3 shadow-sm border-0">
                                <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                ✅ Sudah Dikerjakan <br>
                                <strong>Nilai Anda: <span class="fs-3 d-block mt-1 text-success">{{ round($hasil[$u->id]->nilai) }} / 100</span></strong>
                            </div>
                        @else
                            @php $sekarang = \Carbon\Carbon::now(); @endphp

                            @if($sekarang->lt($u->waktu_mulai))
                                <button class="btn btn-secondary w-100 fw-bold disabled py-2 shadow-sm">
                                    <i class="fas fa-hourglass-half me-1"></i> ⏳ Belum Dimulai
                                </button>
                            @elseif($sekarang->gt($u->waktu_selesai))
                                <button class="btn btn-danger w-100 fw-bold disabled py-2 shadow-sm">
                                    <i class="fas fa-times-circle me-1"></i> ❌ Waktu Habis
                                </button>
                            @else
                                <a href="{{ route('siswa.ujian.kerjakan', $u->id) }}" class="btn bg-purple text-white w-100 fw-bold py-2 shadow" onclick="return confirm('Apakah Anda yakin ingin memulai ujian sekarang? Waktu akan terus berjalan!')">
                                    🚀 Mulai Kerjakan Sekarang
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-12">
                <div class="alert alert-light text-center p-5 shadow-sm rounded-4 border">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-bold mb-0">Belum Ada Jadwal Ujian</h5>
                    <p class="text-muted small">Guru belum menambahkan ujian untuk mata pelajaran ini.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
