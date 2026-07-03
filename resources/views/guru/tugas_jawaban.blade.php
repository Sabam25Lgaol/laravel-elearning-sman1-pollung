@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-primary mb-1 fw-bold"><i class="fas fa-check-circle me-2"></i>Penilaian Tugas: {{ $tugas->judul_tugas }}</h4>
                    <p class="text-muted mb-0"><i class="fas fa-book me-1"></i> Mata Pelajaran: <strong>{{ $tugas->pelajaran->nama_pelajaran }}</strong></p>
                </div>
                <a href="{{ route('guru.tugas', $tugas->pelajaran_id) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
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
        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-header bg-white fw-bold text-dark">
                <i class="fas fa-users me-1"></i> Daftar Siswa yang Mengumpulkan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Nama Siswa</th>
                                <th>Waktu Pengumpulan</th>
                                <th>File & Jawaban</th>
                                <th>Status Nilai</th>
                                <th>Aksi / Beri Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengumpulans as $p)
                            <tr>
                                <td class="px-4 fw-bold text-primary">
                                    <i class="fas fa-user-graduate me-1 text-muted"></i> {{ $p->siswa->name }}
                                </td>

                                <td>
                                    @if(\Carbon\Carbon::parse($p->created_at)->gt($tugas->tenggat_waktu))
                                        <span class="badge bg-danger shadow-sm py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $p->created_at->format('d M Y, H:i') }} (Terlambat)
                                        </span>
                                    @else
                                        <span class="badge bg-success shadow-sm py-2">
                                            <i class="fas fa-check me-1"></i> {{ $p->created_at->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($p->file_jawaban)
                                        <a href="{{ asset('uploads/jawaban/' . $p->file_jawaban) }}" target="_blank" class="btn btn-sm btn-info text-white fw-bold shadow-sm mb-1">
                                            <i class="fas fa-eye me-1"></i> Lihat File
                                        </a>
                                    @else
                                        <span class="text-muted small fst-italic">Tidak ada lampiran</span>
                                    @endif

                                    @if($p->catatan_siswa)
                                        <div class="small text-muted mt-1 bg-light p-2 rounded border-start border-3 border-info">
                                            "{{ Str::limit($p->catatan_siswa, 50, '...') }}"
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($p->nilai !== null)
                                        <span class="badge bg-success fs-6 shadow-sm">{{ $p->nilai }} / 100</span>
                                    @else
                                        <span class="badge bg-secondary shadow-sm">Belum Dinilai</span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-warning fw-bold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNilai{{ $p->id }}">
                                        <i class="fas fa-pen me-1"></i> Beri Nilai
                                    </button>

                                    <div class="modal fade text-start" id="modalNilai{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('guru.tugas.nilai', $p->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title text-dark fw-bold">
                                                            <i class="fas fa-award me-1"></i> Nilai: {{ $p->siswa->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-save me-1"></i> Simpan Nilai</button>
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
