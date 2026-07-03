@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-4 border-primary">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-purple"><i class="fas fa-clipboard-check me-2"></i>Daftar Nilai: {{ $ujian->judul_ujian }}</h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-book me-1"></i> Pelajaran: <strong>{{ $ujian->pelajaran->nama_pelajaran }}</strong>
                    </p>
                </div>
                <a href="{{ route('guru.ujian', $ujian->pelajaran_id) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Ujian
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-12">
        <div class="card shadow-sm border-0 border-top border-4 border-primary">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-users me-1 text-secondary"></i> Daftar Siswa & Status Ujian
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 text-center" style="min-width: 60px;">No</th>
                                <th style="min-width: 250px;">Nama Siswa</th>
                                <th style="min-width: 150px;">NIS / Induk</th>
                                <th class="text-center" style="min-width: 200px;">Status Mengerjakan</th>
                                <th class="text-center" style="min-width: 120px;">Nilai Akhir</th>
                                <th class="text-center" style="min-width: 220px;">Aksi Koreksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswas as $index => $siswa)
                            @php
                                // Cek apakah siswa ini ada di dalam tabel hasil ujian?
                                $hasil = isset($hasil_ujians[$siswa->id]) ? $hasil_ujians[$siswa->id] : null;
                            @endphp
                            <tr>
                                <td class="px-4 text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold text-primary">{{ $siswa->name }}</td>
                                <td class="text-muted">{{ $siswa->nomor_induk ?? '-' }}</td>

                                <td class="text-center">
                                    @if($hasil)
                                        <span class="badge bg-success shadow-sm px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm px-3 py-2 opacity-75"><i class="fas fa-clock me-1"></i> Belum/Tidak Mengerjakan</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($hasil)
                                        <span class="fs-5 fw-bold {{ $hasil->nilai >= 75 ? 'text-success' : 'text-danger' }}">
                                            {{ round($hasil->nilai) }}
                                        </span>
                                    @else
                                        <span class="text-muted fw-bold">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($hasil)
                                        <a href="{{ route('guru.ujian.koreksi', ['ujian_id' => $ujian->id, 'siswa_id' => $siswa->id]) }}" class="btn btn-sm bg-purple text-white fw-bold shadow-sm rounded-pill px-3 transition-all">
                                            <i class="fas fa-search me-1"></i> Lihat / Koreksi Jawaban
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-light fw-bold text-muted border rounded-pill px-3" disabled>Belum Ada Jawaban</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                    <h6>Belum ada siswa di kelas ini.</h6>
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
