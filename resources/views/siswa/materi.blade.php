@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="text-info mb-1 fw-bold"><i class="fas fa-book-open me-2"></i>Materi Pelajaran: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">{{ $pelajaran->deskripsi ?? 'Silakan pelajari materi di bawah ini dengan saksama.' }}</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-list me-1 text-info"></i> Daftar Materi yang Tersedia
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3" style="min-width: 220px;">Judul Materi</th>
                                <th class="py-3" style="min-width: 150px;">Lampiran</th>
                                <th class="py-3" style="min-width: 120px;">Tgl Upload</th>
                                <th class="py-3 text-center" style="min-width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materis as $materi)
                            <tr>
                                <td class="px-4">
                                    <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold text-info fs-6 text-start" data-bs-toggle="modal" data-bs-target="#previewMateri{{ $materi->id }}">
                                        {{ $materi->judul_materi }}
                                    </button>
                                    @if($materi->isi_materi)
                                        <br><small class="text-muted fw-normal"><i class="fas fa-align-left me-1"></i> Ada catatan teks</small>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        @if($materi->file_materi)
                                            <a href="{{ asset('uploads/materi/' . $materi->file_materi) }}" target="_blank" rel="noopener noreferrer" class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill shadow-sm text-decoration-none" title="Buka lampiran">
                                                <i class="fas fa-file-alt me-1"></i> File
                                            </a>
                                        @endif

                                        @if($materi->link_youtube)
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fab fa-youtube me-1"></i> Video
                                            </span>
                                        @endif

                                        @if(!$materi->file_materi && !$materi->link_youtube)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Teks</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="small text-muted fw-bold">{{ $materi->created_at->format('d M Y') }}</td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white fw-bold rounded-pill shadow-sm transition-all" data-bs-toggle="modal" data-bs-target="#previewMateri{{ $materi->id }}">
                                        <i class="fas fa-eye me-1"></i> Buka
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                    <h6 class="fw-bold">Belum Ada Materi</h6>
                                    <small>Bapak/Ibu Guru belum mengunggah materi apapun untuk pelajaran ini. Silakan cek kembali nanti!</small>
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

@foreach($materis as $materi)
<div class="modal fade" id="previewMateri{{ $materi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-book-reader me-2"></i>{{ $materi->judul_materi }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">

                @if($materi->link_youtube)
                    @php
                        $yt_id = '';
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $materi->link_youtube, $match);
                        $yt_id = isset($match[1]) ? $match[1] : '';
                    @endphp

                    @if($yt_id)
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark border-bottom border-2 border-info pb-2 mb-3">
                            <i class="fab fa-youtube text-danger me-2"></i>Video Pembelajaran
                        </h6>
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                            <iframe src="https://www.youtube.com/embed/{{ $yt_id }}" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif
                @endif

                @if($materi->isi_materi)
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark border-bottom border-2 border-info pb-2 mb-3">
                            <i class="fas fa-align-left text-info me-2"></i>Penjelasan dari Guru
                        </h6>
                        <div class="bg-white p-3 rounded-4 shadow-sm text-secondary" style="white-space: pre-wrap; font-size: 15px; line-height: 1.6;">{{ $materi->isi_materi }}</div>
                    </div>
                @endif

                @if($materi->file_materi)
                    <div class="mt-4 text-center">
                        <a href="{{ asset('uploads/materi/' . $materi->file_materi) }}" target="_blank" rel="noopener noreferrer" class="btn btn-info text-white fw-bold rounded-pill px-5 py-2 shadow-sm transition-all">
                            <i class="fas fa-download me-2"></i> Lihat / Download File Materi
                        </a>
                    </div>
                @endif

                @if(!$materi->isi_materi && !$materi->file_materi && !$materi->link_youtube)
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                        <p>Materi ini belum memiliki isi teks, dokumen, maupun video.</p>
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
