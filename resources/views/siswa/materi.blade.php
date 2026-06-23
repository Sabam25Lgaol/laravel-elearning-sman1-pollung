@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-0 bg-gradient-info text-white shadow-sm rounded-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="fas fa-book-reader me-2"></i>Materi Pelajaran: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="mb-0 opacity-75"><i class="fas fa-info-circle me-1"></i> {{ $pelajaran->deskripsi ?? 'Silakan pelajari materi di bawah ini dengan saksama.' }}</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <h5 class="fw-bold text-dark border-bottom pb-2 border-2 border-info d-inline-block">Daftar Materi Pembelajaran</h5>
    </div>

    @forelse($materis as $materi)
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 rounded-4 card-materi">
            <div class="card-header bg-white border-bottom pb-0 pt-4 px-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-2">
                    <i class="fas fa-bookmark text-info me-2"></i> {{ $materi->judul_materi }}
                </h5>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill shadow-sm">
                    <i class="far fa-calendar-alt me-1"></i> Diunggah: {{ $materi->created_at->format('d M Y') }}
                </span>
            </div>

            <div class="card-body px-4 py-4">
                @if($materi->isi_materi)
                    <div class="bg-light p-3 rounded-3 border-start border-4 border-info mb-4 shadow-sm text-dark materi-teks">
                        {{ $materi->isi_materi }}
                    </div>
                @else
                    <div class="alert alert-light border text-muted fst-italic mb-4">
                        <i class="fas fa-info-circle me-1"></i> Tidak ada penjelasan teks tambahan dari Guru.
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-3">
                    @if($materi->file_materi)
                        <a href="{{ asset('uploads/materi/' . $materi->file_materi) }}" target="_blank" class="btn btn-primary fw-bold shadow-sm px-4 py-2 btn-lampiran">
                            <i class="fas fa-file-download me-2"></i> Download Materi
                        </a>
                    @endif

                    @if($materi->link_youtube)
                        <a href="{{ $materi->link_youtube }}" target="_blank" class="btn btn-danger fw-bold shadow-sm px-4 py-2 btn-lampiran">
                            <i class="fab fa-youtube me-2"></i> Tonton Video
                        </a>
                    @endif

                    @if(!$materi->file_materi && !$materi->link_youtube)
                        <span class="badge bg-light text-muted border px-3 py-2 align-self-center">
                            <i class="fas fa-unlink me-1"></i> Tidak ada lampiran file/video.
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="card shadow-sm border-0 bg-light rounded-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x mb-3 text-muted opacity-25"></i>
                <h5 class="fw-bold text-muted">Belum Ada Materi</h5>
                <p class="text-muted mb-0">Bapak/Ibu Guru belum mengunggah materi apapun untuk pelajaran ini. Silakan cek kembali nanti!</p>
            </div>
        </div>
    </div>
    @endforelse

</div>
@endsection
