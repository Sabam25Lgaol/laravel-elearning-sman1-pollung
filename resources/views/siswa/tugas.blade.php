@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-0 bg-gradient-warning text-white shadow-sm rounded-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-tasks me-2"></i>Daftar Tugas: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="mb-0 text-dark opacity-75"><i class="fas fa-bullhorn me-1"></i> Kerjakan dan kumpulkan tugasmu sebelum batas waktu (deadline) berakhir!</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-dark fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm rounded-4 border-0 border-start border-danger border-5">
            <i class="fas fa-exclamation-triangle me-2"></i> Gagal mengirim tugas. Periksa kesalahan berikut:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-3">
        <h5 class="fw-bold text-dark border-bottom pb-2 border-2 tugas-title-border">Daftar Tugas Aktif & Riwayat</h5>
    </div>

    <div class="col-md-12">
        <div class="row">
            @forelse($tugas as $t)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100 card-tugas">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-start gap-2 flex-wrap">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-thumbtack text-warning me-2"></i> {{ $t->judul_tugas }}
                        </h5>
                        @if(\Carbon\Carbon::now()->gt($t->tenggat_waktu))
                            <span class="badge bg-danger shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-times-circle me-1"></i> Waktu Habis</span>
                        @else
                            <span class="badge bg-warning text-dark shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-stopwatch me-1"></i> Berakhir: {{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('d M Y, H:i') }}</span>
                        @endif
                    </div>

                    <div class="card-body px-4 pb-4 d-flex flex-column">
                        <div class="bg-light p-3 rounded-3 border-start border-4 border-warning mb-3 text-dark flex-grow-1 tugas-deskripsi">
                            {{ $t->deskripsi }}
                        </div>

                        @if($t->file_tugas)
                            <div class="mb-4">
                                <a href="{{ asset('uploads/tugas/' . $t->file_tugas) }}" target="_blank" class="btn btn-outline-dark btn-sm fw-bold rounded-pill px-3 shadow-sm hover-warning">
                                    <i class="fas fa-file-download me-1"></i> Download File Soal
                                </a>
                            </div>
                        @endif

                        <div class="mt-auto border-top pt-3">
                            @if(isset($pengumpulan[$t->id]))
                                <div class="bg-success bg-opacity-10 border border-success rounded-3 p-3 text-center shadow-sm">
                                    <h6 class="text-success fw-bold mb-2"><i class="fas fa-check-double me-1"></i> Tugas Berhasil Dikumpulkan!</h6>

                                    @if($pengumpulan[$t->id]->nilai !== null)
                                        <div class="mt-2 bg-white rounded p-2 d-inline-block border border-success">
                                            <span class="text-muted small">Nilai Anda:</span>
                                            <strong class="fs-3 text-success ms-2">{{ $pengumpulan[$t->id]->nilai }}</strong> <span class="text-muted">/ 100</span>
                                        </div>
                                    @else
                                        <div class="mt-2 text-muted fst-italic small">
                                            <i class="fas fa-hourglass-half me-1"></i> Menunggu penilaian dari Bapak/Ibu Guru.
                                        </div>
                                    @endif
                                </div>
                            @else
                                @if(\Carbon\Carbon::now()->lt($t->tenggat_waktu))
                                    <form action="{{ route('siswa.tugas.submit', $t->id) }}" method="POST" enctype="multipart/form-data" class="form-tugas">
                                        @csrf
                                        <div class="file-upload-box mb-3">
                                            <label class="form-label small fw-bold text-dark"><i class="fas fa-cloud-upload-alt text-warning me-1"></i> Upload File Jawaban (PDF/Word/ZIP)</label>
                                            <input type="file" name="file_jawaban" class="form-control form-control-sm border-0 bg-transparent shadow-none @error('file_jawaban') is-invalid @enderror" accept=".pdf,.doc,.docx,.zip,.rar" required>
                                            @error('file_jawaban')
                                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark"><i class="fas fa-comment-dots text-warning me-1"></i> Catatan untuk Guru (Opsional)</label>
                                            <textarea name="catatan_siswa" class="form-control form-control-sm shadow-sm @error('catatan_siswa') is-invalid @enderror" rows="2" placeholder="Tambahkan pesan jika diperlukan..."></textarea>
                                            @error('catatan_siswa')
                                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-warning text-dark fw-bold w-100 shadow-sm rounded-3 py-2 btn-submit-tugas">
                                            <i class="fas fa-paper-plane me-1"></i> Kirim Jawaban Sekarang
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-danger bg-opacity-10 border border-danger rounded-3 p-3 text-center shadow-sm">
                                        <h6 class="text-danger fw-bold mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Maaf, batas waktu pengumpulan telah terlewat.</h6>
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="col-md-12">
                <div class="card shadow-sm border-0 bg-light rounded-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-mug-hot fa-4x mb-3 text-muted opacity-25"></i>
                        <h5 class="fw-bold text-muted">Belum Ada Tugas</h5>
                        <p class="text-muted mb-0">Hore! Belum ada tugas untuk pelajaran ini. Santai dulu dan nikmati harimu! ☕</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // PROTEKSI UI 3: JavaScript Anti-Double Click untuk multiple form dalam satu halaman
    document.addEventListener("DOMContentLoaded", function() {
        let forms = document.querySelectorAll('.form-tugas');

        forms.forEach(function(form) {
            form.addEventListener("submit", function(e) {
                let btn = form.querySelector(".btn-submit-tugas");

                if (!btn.disabled) {
                    if (confirm('Yakin ingin mengirim jawaban ini? File besar mungkin memerlukan waktu proses.')) {
                        // Kunci tombol visual dan fungsi
                        btn.disabled = true;
                        btn.classList.add("opacity-75");
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengunggah File...';
                    } else {
                        // Batal konfirmasi
                        e.preventDefault();
                    }
                } else {
                    // Blokir klik brutal
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection
