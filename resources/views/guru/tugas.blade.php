@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-warning text-dark mb-1 fw-bold"><i class="fas fa-tasks me-2"></i>Kelola Tugas: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Berikan tugas, atur batas waktu, dan pantau pengumpulan siswa.</p>
                </div>
                <a href="{{ route('guru.tugas.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm fw-bold border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-warning text-dark fw-bold py-3 border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <i class="fas fa-plus-circle me-1"></i> Buat Tugas Baru
            </div>
            <div class="card-body p-4">
                <form action="{{ route('guru.tugas.store', $pelajaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" name="judul_tugas" class="form-control shadow-sm" placeholder="Contoh: Latihan Bab 1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi / Instruksi Soal <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control shadow-sm" rows="4" placeholder="Tuliskan instruksi tugas dengan jelas..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">File Lampiran Soal (Opsional)</label>
                        <input type="file" name="file_tugas" class="form-control shadow-sm" accept=".pdf,.doc,.docx,.zip,.rar">
                        <small class="text-danger" style="font-size: 11px;"><i class="fas fa-info-circle"></i> Maksimal 5MB (PDF/Word/ZIP).</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Batas Waktu (Deadline) <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tenggat_waktu" class="form-control shadow-sm" required>
                    </div>

                    <button type="submit" class="btn btn-warning fw-bold text-dark w-100 shadow-sm rounded-pill py-2 transition-all">
                        <i class="fas fa-paper-plane me-1"></i> Terbitkan Tugas
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden border-top border-warning border-4">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-list me-1 text-warning"></i> Daftar Tugas yang Diberikan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3" style="min-width: 200px;">Judul Tugas</th>
                                <th class="py-3" style="min-width: 150px;">Deadline</th>
                                <th class="py-3 text-center" style="min-width: 100px;">Lampiran</th>
                                <th class="py-3 text-center" style="min-width: 250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugas as $t)
                            <tr>
                                <td class="px-4">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-primary fs-6 text-start" data-bs-toggle="modal" data-bs-target="#previewTugas{{ $t->id }}">
                                        {{ $t->judul_tugas }}
                                    </button>
                                    <br>
                                    <small class="text-muted fw-normal" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#previewTugas{{ $t->id }}">
                                        <i class="fas fa-eye me-1"></i> Klik untuk lihat instruksi
                                    </small>
                                </td>

                                <td>
                                    @php
                                        $deadline = \Carbon\Carbon::parse($t->tenggat_waktu);
                                        $is_past = $deadline->isPast();
                                    @endphp
                                    <span class="badge {{ $is_past ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill shadow-sm">
                                        <i class="far fa-clock me-1"></i> {{ $deadline->format('d M Y, H:i') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($t->file_tugas)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fas fa-paperclip"></i> Ada
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('guru.tugas.jawaban', $t->id) }}" class="btn btn-sm btn-success fw-bold rounded-pill px-3 shadow-sm transition-all">
                                            <i class="fas fa-user-check me-1"></i> Cek Jawaban
                                        </a>

                                        <button type="button" class="btn btn-sm btn-warning fw-bold text-dark rounded-pill px-3 shadow-sm transition-all" data-bs-toggle="modal" data-bs-target="#editTugas{{ $t->id }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('guru.tugas.delete', $t->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold rounded-pill px-3 shadow-sm transition-all" onclick="return confirm('Yakin ingin menghapus tugas ini? Semua data pengumpulan siswa untuk tugas ini juga akan terhapus permanen.')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                                    <h6>Belum ada tugas yang diberikan.</h6>
                                    <small>Silakan buat tugas pertama Anda melalui form di sebelah kiri.</small>
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

@foreach($tugas as $t)

    <div class="modal fade" id="previewTugas{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-warning border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-clipboard-check me-2"></i>Detail Tugas: {{ $t->judul_tugas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">

                    <div class="mb-4 d-flex align-items-center gap-2 border-bottom border-warning pb-3">
                        <span class="fw-bold text-dark">Batas Waktu Pengumpulan:</span>
                        @php
                            $deadline = \Carbon\Carbon::parse($t->tenggat_waktu);
                            $is_past = $deadline->isPast();
                        @endphp
                        <span class="badge {{ $is_past ? 'bg-danger' : 'bg-success' }} px-3 py-2 rounded-pill fs-6 shadow-sm">
                            <i class="far fa-clock me-1"></i> {{ $deadline->format('l, d F Y - H:i') }} WIB
                        </span>
                        @if($is_past)
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill small">Sudah Ditutup</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-align-left text-warning me-2"></i>Instruksi / Deskripsi Soal:</h6>
                        <div class="bg-white p-3 rounded-4 shadow-sm text-secondary" style="white-space: pre-wrap; font-size: 15px; line-height: 1.6;">{{ $t->deskripsi }}</div>
                    </div>

                    @if($t->file_tugas)
                        <div class="mt-4 text-center">
                            <h6 class="fw-bold text-dark mb-3">Terdapat File Lampiran Soal:</h6>
                            <a href="{{ asset('uploads/tugas/' . $t->file_tugas) }}" target="_blank" class="btn btn-primary fw-bold rounded-pill px-5 py-2 shadow-sm transition-all">
                                <i class="fas fa-download me-2"></i> Download Lampiran Tugas
                            </a>
                        </div>
                    @endif

                </div>
                <div class="modal-footer bg-white border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup Preview</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTugas{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-warning border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2"></i>Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('guru.tugas.update', $t->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-start p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Judul Tugas *</label>
                            <input type="text" name="judul_tugas" class="form-control shadow-sm" value="{{ $t->judul_tugas }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Deskripsi / Instruksi Soal *</label>
                            <textarea name="deskripsi" class="form-control shadow-sm" rows="4" required>{{ $t->deskripsi }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Ganti File Lampiran (Opsional)</label>
                            <input type="file" name="file_tugas" class="form-control shadow-sm" accept=".pdf,.doc,.docx,.zip,.rar">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Biarkan kosong jika tidak ingin mengganti file lama.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Batas Waktu (Deadline) *</label>
                            <input type="datetime-local" name="tenggat_waktu" class="form-control shadow-sm" value="{{ date('Y-m-d\TH:i', strtotime($t->tenggat_waktu)) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endforeach
@endsection
