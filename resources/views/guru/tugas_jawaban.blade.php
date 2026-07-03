@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-info mb-1 fw-bold text-break"><i class="fas fa-check-circle me-2"></i>Penilaian Tugas: {{ $tugas->judul_tugas }}</h4>
                    <p class="text-muted mb-0"><i class="fas fa-book me-1"></i> Mata Pelajaran: <strong>{{ $tugas->pelajaran->nama_pelajaran }}</strong></p>
                </div>
                <a href="{{ route('guru.tugas', $tugas->pelajaran_id) }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4 py-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Tugas
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm fw-bold" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-users me-1 text-info"></i> Daftar Siswa yang Mengumpulkan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4" style="min-width: 220px;">Nama Siswa</th>
                                <th style="min-width: 190px;">Waktu Pengumpulan</th>
                                <th class="text-center" style="min-width: 150px;">Kiriman Siswa</th>
                                <th style="min-width: 130px;">Status Nilai</th>
                                <th class="text-center" style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengumpulans as $p)
                            <tr>
                                <td class="px-4">
                                    <button type="button" class="btn btn-link p-0 fw-bold text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalJawaban{{ $p->id }}">
                                        <i class="fas fa-user-graduate me-1 text-muted"></i> {{ $p->siswa->name }}
                                    </button>
                                </td>

                                <td>
                                    @if(\Carbon\Carbon::parse($p->created_at)->gt($tugas->tenggat_waktu))
                                        <span class="badge bg-danger shadow-sm py-2 px-3 rounded-pill">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $p->created_at->format('d M Y, H:i') }} (Terlambat)
                                        </span>
                                    @else
                                        <span class="badge bg-success shadow-sm py-2 px-3 rounded-pill">
                                            <i class="fas fa-check me-1"></i> {{ $p->created_at->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white fw-bold shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalJawaban{{ $p->id }}">
                                        <i class="fas fa-folder-open me-1"></i> Lihat Jawaban
                                    </button>
                                </td>

                                <td>
                                    @if($p->nilai !== null)
                                        <span class="badge bg-success fs-6 shadow-sm rounded-pill px-3 py-2">{{ $p->nilai }} / 100</span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm rounded-pill px-3 py-2">Belum Dinilai</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-info fw-bold shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalNilai{{ $p->id }}">
                                        <i class="fas fa-pen me-1"></i> Beri Nilai
                                    </button>

                                    <div class="modal fade text-start" id="modalJawaban{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                                                <div class="modal-header bg-info border-0">
                                                    <div>
                                                        <h5 class="modal-title text-white fw-bold">
                                                            <i class="fas fa-user-graduate me-1"></i> Jawaban {{ $p->siswa->name }}
                                                        </h5>
                                                        <small class="text-white-50">Dikumpulkan pada {{ $p->created_at->format('d M Y, H:i') }}</small>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <div class="guru-submission-meta h-100">
                                                                <div class="small text-muted fw-bold text-uppercase mb-1">Status Nilai</div>
                                                                @if($p->nilai !== null)
                                                                    <span class="badge bg-success rounded-pill px-3 py-2">{{ $p->nilai }} / 100</span>
                                                                @else
                                                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Belum Dinilai</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="guru-submission-meta h-100">
                                                                <div class="small text-muted fw-bold text-uppercase mb-1">Lampiran File</div>
                                                                @if($p->file_jawaban)
                                                                    <a href="{{ asset('uploads/jawaban/' . $p->file_jawaban) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info text-white fw-bold rounded-pill px-3">
                                                                        <i class="fas fa-eye me-1"></i> Buka File Jawaban
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted small fst-italic">Tidak ada lampiran</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="fw-bold text-dark mb-2">
                                                        <i class="fas fa-align-left me-1 text-info"></i> Jawaban Teks Siswa
                                                    </div>
                                                    @if($p->catatan_siswa)
                                                        <div class="guru-student-answer-modal text-dark bg-light p-3 rounded-3 border-start border-3 border-info">
                                                            {{ $p->catatan_siswa }}
                                                        </div>
                                                    @else
                                                        <div class="text-muted fst-italic bg-light p-3 rounded-3">Tidak ada jawaban teks.</div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                                    <button type="button" class="btn btn-info text-white fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNilai{{ $p->id }}">
                                                        <i class="fas fa-pen me-1"></i> Beri Nilai
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade text-start" id="modalNilai{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('guru.tugas.nilai', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                                                    <div class="modal-header bg-info border-0">
                                                        <h5 class="modal-title text-white fw-bold">
                                                            <i class="fas fa-award me-1"></i> Nilai: {{ $p->siswa->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info small mb-3">
                                                            <strong>Tugas:</strong> {{ $tugas->judul_tugas }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Masukkan Nilai (0-100) <span class="text-danger">*</span></label>
                                                            <input type="number" name="nilai" class="form-control form-control-lg text-center fw-bold text-success" value="{{ $p->nilai }}" min="0" max="100" placeholder="0" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Komentar / Umpan Balik Guru (Opsional)</label>
                                                            <textarea name="catatan_guru" class="form-control" rows="3" placeholder="Berikan saran atau apresiasi atas tugas ini...">{{ $p->catatan_guru }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light border-0">
                                                        <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-4"><i class="fas fa-save me-1"></i> Simpan Nilai</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                    <h6>Belum ada siswa yang mengumpulkan tugas ini.</h6>
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
