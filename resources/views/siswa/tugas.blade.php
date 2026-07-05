@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="text-info mb-1 fw-bold"><i class="fas fa-tasks me-2"></i>Daftar Tugas: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Kerjakan dan kumpulkan tugasmu sebelum batas waktu (deadline) berakhir!</p>
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

    @if($errors->any())
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm rounded-3 border-0">
            <i class="fas fa-exclamation-triangle me-2"></i> Gagal mengirim tugas. Periksa kesalahan berikut:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-list me-1 text-info"></i> Daftar Tugas Aktif & Riwayat
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3" style="min-width: 220px;">Judul Tugas</th>
                                <th class="py-3" style="min-width: 170px;">Deadline</th>
                                <th class="py-3 text-center" style="min-width: 190px;">Status</th>
                                <th class="py-3 text-center" style="min-width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugas as $t)
                            @php
                                $sudahLewat = \Carbon\Carbon::now()->gt($t->tenggat_waktu);
                                $kumpul = $pengumpulan[$t->id] ?? null;
                            @endphp
                            <tr>
                                <td class="px-4">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-info fs-6 text-start" data-bs-toggle="modal" data-bs-target="#detailTugas{{ $t->id }}">
                                        {{ $t->judul_tugas }}
                                    </button>
                                    <br>
                                    <small class="text-muted fw-normal" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#detailTugas{{ $t->id }}">
                                        <i class="fas fa-eye me-1"></i> Klik untuk lihat instruksi
                                    </small>
                                </td>

                                <td>
                                    @if($sudahLewat)
                                        <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-times-circle me-1"></i> Waktu Habis</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-stopwatch me-1"></i> {{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('d M Y, H:i') }}</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($kumpul && $kumpul->nilai !== null)
                                        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-check-double me-1"></i> Nilai: {{ $kumpul->nilai }}</span>
                                    @elseif($kumpul)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill"><i class="fas fa-check me-1"></i> Terkumpul</span>
                                    @elseif($sudahLewat)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">Tidak Mengumpulkan</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Belum Dikumpulkan</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white fw-bold rounded-pill shadow-sm transition-all" data-bs-toggle="modal" data-bs-target="#detailTugas{{ $t->id }}">
                                        <i class="fas fa-folder-open me-1"></i> Buka
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-mug-hot fa-3x mb-3 opacity-25"></i>
                                    <h6 class="fw-bold">Belum Ada Tugas</h6>
                                    <small>Hore! Belum ada tugas untuk pelajaran ini. Santai dulu dan nikmati harimu! ☕</small>
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
@php
    $kumpul = $pengumpulan[$t->id] ?? null;
    $sudahLewat = \Carbon\Carbon::now()->gt($t->tenggat_waktu);
@endphp
<div class="modal fade" id="detailTugas{{ $t->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-clipboard-check me-2"></i>{{ $t->judul_tugas }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">

                <div class="mb-4 d-flex align-items-center gap-2 border-bottom border-info pb-3 flex-wrap">
                    <span class="fw-bold text-dark">Batas Waktu Pengumpulan:</span>
                    <span class="badge {{ $sudahLewat ? 'bg-danger' : 'bg-success' }} px-3 py-2 rounded-pill fs-6 shadow-sm">
                        <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('l, d F Y - H:i') }} WIB
                    </span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-align-left text-info me-2"></i>Instruksi / Deskripsi Soal:</h6>
                    <div class="bg-white p-3 rounded-4 shadow-sm text-secondary" style="white-space: pre-wrap; font-size: 15px; line-height: 1.6;">{{ $t->deskripsi }}</div>
                </div>

                @if($t->file_tugas)
                    <div class="mb-4 text-center">
                        <a href="{{ asset('uploads/tugas/' . $t->file_tugas) }}" target="_blank" class="btn btn-outline-info fw-bold rounded-pill px-4 shadow-sm">
                            <i class="fas fa-file-download me-1"></i> Download File Soal
                        </a>
                    </div>
                @endif

                <hr class="text-muted">

                @if($kumpul)
                    @if($kumpul->nilai !== null)
                        {{-- SUDAH DINILAI --}}
                        <div class="bg-success bg-opacity-10 border border-success rounded-4 p-3 text-center shadow-sm">
                            <h6 class="text-success fw-bold mb-2"><i class="fas fa-check-double me-1"></i> Sudah Dinilai Guru</h6>
                            <div class="mt-2 bg-white rounded p-2 d-inline-block border">
                                <span class="text-muted small">Nilai Akhir:</span>
                                <strong class="fs-3 text-success ms-2">{{ $kumpul->nilai }}</strong>
                            </div>
                            <p class="small text-muted mb-0 mt-2">Dikumpulkan pada: {{ $kumpul->updated_at->format('d M Y, H:i') }}</p>
                            @if($kumpul->catatan_guru)
                                <div class="mt-3 text-start bg-white p-3 rounded border shadow-sm">
                                    <p class="fw-bold text-dark mb-1"><i class="fas fa-comment-alt-dots text-info me-1"></i> Catatan dari Guru:</p>
                                    <p class="text-muted mb-0 fst-italic">{{ $kumpul->catatan_guru }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- SUDAH DIKUMPULKAN, BELUM DINILAI --}}
                        <div class="alert alert-success text-center py-2 mb-3">
                            <i class="fas fa-check-circle me-1"></i> <strong>Terkumpul!</strong> Menunggu penilaian.
                        </div>
                        <p class="mb-1 small text-muted">Jawaban Anda: <strong class="text-dark">{{ Str::limit($kumpul->file_jawaban, 30) }}</strong></p>
                        <p class="small text-muted mb-3">Dikumpulkan pada: {{ $kumpul->updated_at->format('d M Y, H:i') }}</p>

                        @if(!$sudahLewat)
                            <p class="text-center small text-muted fst-italic mb-2">Anda masih bisa mengganti file jawaban.</p>
                            <form action="{{ route('siswa.tugas.submit', $t->id) }}" method="POST" enctype="multipart/form-data" class="form-tugas">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="file_jawaban" class="form-control @error('file_jawaban') is-invalid @enderror" required>
                                    <button type="submit" class="btn btn-outline-info btn-submit-tugas" title="Ganti File Jawaban"><i class="fas fa-sync-alt"></i> Ganti</button>
                                </div>
                                @error('file_jawaban') <div class="invalid-feedback d-block fw-bold">{{ $message }}</div> @enderror
                            </form>
                        @endif
                    @endif
                @else
                    @if(!$sudahLewat)
                        {{-- BELUM MENGUMPULKAN, WAKTU MASIH ADA --}}
                        <form action="{{ route('siswa.tugas.submit', $t->id) }}" method="POST" enctype="multipart/form-data" class="form-tugas">
                            @csrf
                            <div class="mb-3">
                                <label for="file-{{$t->id}}" class="form-label small fw-bold text-dark"><i class="fas fa-cloud-upload-alt text-info me-1"></i> Upload File Jawaban</label>
                                <input type="file" name="file_jawaban" id="file-{{$t->id}}" class="form-control @error('file_jawaban') is-invalid @enderror" accept=".pdf,.doc,.docx,.zip,.rar" required>
                                <div class="form-text">Jenis file yang diizinkan: PDF, Word, ZIP, RAR.</div>
                                @error('file_jawaban')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark"><i class="fas fa-comment-dots text-info me-1"></i> Catatan untuk Guru <span class="text-muted">(Opsional)</span></label>
                                <textarea name="catatan_siswa" class="form-control form-control-sm shadow-sm @error('catatan_siswa') is-invalid @enderror" rows="2" placeholder="Tambahkan pesan jika diperlukan..."></textarea>
                                @error('catatan_siswa')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-info text-white fw-bold w-100 shadow-sm rounded-pill py-2 btn-submit-tugas">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Jawaban Sekarang
                            </button>
                        </form>
                    @else
                        {{-- WAKTU HABIS, BELUM MENGUMPULKAN --}}
                        <div class="bg-danger bg-opacity-10 border border-danger rounded-4 p-3 text-center shadow-sm">
                            <h6 class="text-danger fw-bold mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Maaf, batas waktu pengumpulan telah terlewat.</h6>
                        </div>
                    @endif
                @endif

            </div>
            <div class="modal-footer bg-white border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    // PROTEKSI UI 3: JavaScript Anti-Double Click untuk multiple form dalam satu halaman
    document.addEventListener("DOMContentLoaded", function() {
        let forms = document.querySelectorAll('.form-tugas');

        forms.forEach(function(form) {
            form.addEventListener("submit", function(e) {
                let btn = form.querySelector(".btn-submit-tugas");

                if (!btn.disabled) {
                    if (confirm('Yakin ingin mengirim jawaban ini? File besar mungkin memerlukan waktu proses.')) {
                        btn.disabled = true;
                        btn.classList.add("opacity-75");
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengunggah File...';
                    } else {
                        e.preventDefault();
                    }
                } else {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection
