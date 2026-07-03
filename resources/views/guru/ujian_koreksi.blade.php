@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-info"><i class="fas fa-user-edit me-2"></i>Koreksi Jawaban: {{ $siswa->name }}</h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-file-alt me-1"></i> Ujian: <strong>{{ $ujian->judul_ujian }}</strong> |
                        <i class="fas fa-id-badge me-1"></i> NIS/Induk: <strong>{{ $siswa->nomor_induk ?? '-' }}</strong>
                    </p>
                </div>
                <a href="{{ route('guru.ujian.nilai', $ujian->id) }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4 py-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Nilai
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}</div>
    </div>
    @endif

    <div class="col-md-12">
        <form action="{{ route('guru.ujian.simpan_nilai_essay', ['ujian_id' => $ujian->id, 'siswa_id' => $siswa->id]) }}" method="POST">
            @csrf

            @foreach($soals as $index => $soal)
            @php
                $jawaban_siswa = isset($jawabans[$soal->id]) ? $jawabans[$soal->id] : null;
                $teks_jawaban = $jawaban_siswa ? $jawaban_siswa->jawaban_teks : '';
                $skor_saat_ini = $jawaban_siswa ? $jawaban_siswa->skor : 0;
            @endphp

            <div class="card guru-correction-card shadow-sm border-0 mb-4 rounded-4 border-start border-4 border-{{ $soal->jenis_soal == 'essay' ? 'warning' : 'info' }}">
                <div class="card-body p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 flex-wrap gap-2">
                        <h5 class="fw-bold text-dark mb-0">Soal No. {{ $index + 1 }}</h5>
                        @if($soal->jenis_soal == 'essay')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-pen-nib me-1"></i> Essay (Penilaian Manual)</span>
                        @else
                            <span class="badge bg-info px-3 py-2 shadow-sm rounded-pill"><i class="fas fa-robot me-1"></i> Pilihan Ganda (Auto)</span>
                        @endif
                    </div>

                    <div class="guru-question-box mb-3">
                        <div class="small fw-bold text-muted text-uppercase mb-2">
                            <i class="fas fa-question-circle me-1 text-info"></i> Pertanyaan
                        </div>
                        <div class="guru-question-text text-dark lh-base">{{ $soal->pertanyaan }}</div>
                    </div>

                    @if($soal->gambar_soal)
                        <div class="mb-3">
                            <img src="{{ asset('uploads/soal/' . $soal->gambar_soal) }}" alt="Gambar Soal" class="img-fluid rounded shadow-sm border koreksi-image">
                        </div>
                    @endif

                    @if($soal->jenis_soal == 'pilihan_ganda')
                        <div class="guru-pg-summary d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="guru-pg-item">
                                <span class="guru-pg-label"><i class="fas fa-comment-dots me-1 text-info"></i> Jawaban Siswa</span>
                                <span class="guru-pg-value text-info">
                                    {{ (!$jawaban_siswa || empty($teks_jawaban)) ? '-' : $teks_jawaban }}
                                </span>
                            </div>
                            <div class="guru-pg-item">
                                <span class="guru-pg-label"><i class="fas fa-key me-1 text-success"></i> Kunci Jawaban</span>
                                <span class="guru-pg-value text-success">{{ $soal->kunci_jawaban }}</span>
                            </div>
                            <div class="guru-pg-item">
                                <span class="guru-pg-label">Skor Poin</span>
                                <input type="number" class="form-control text-center fw-bold bg-light nilai-input {{ $skor_saat_ini > 0 ? 'text-success border-success' : 'text-danger border-danger' }}" value="{{ $skor_saat_ini }}" readonly>
                            </div>
                        </div>
                    @else
                        <div class="row g-3 align-items-start">
                            <div class="col-lg-8">
                                <div class="guru-answer-panel">
                                    <div class="small fw-bold text-muted text-uppercase mb-2">
                                        <i class="fas fa-comment-dots me-1 text-info"></i> Jawaban Siswa
                                    </div>

                                    @if(!$jawaban_siswa || empty($teks_jawaban))
                                        <span class="text-danger fst-italic">Siswa tidak menjawab / mengosongkan soal ini.</span>
                                    @else
                                        <div class="guru-essay-answer text-dark">{{ $teks_jawaban }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="guru-score-panel">
                                    <label class="small fw-bold text-muted text-uppercase mb-2">Skor Poin</label>
                                    <input type="number" name="skor[{{ $soal->id }}]" class="form-control text-center fw-bold border-warning bg-warning bg-opacity-10 shadow-sm nilai-input" min="0" max="100" value="{{ $skor_saat_ini }}" required>
                                    <small class="text-muted d-block mt-2">Isi 0-100</small>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            @endforeach

            <div class="card guru-save-panel shadow-lg border-0 mb-4 bg-white">
                <div class="card-body d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                    <div>
                        <h6 class="fw-bold text-muted mb-0"><i class="fas fa-calculator me-1"></i> Sistem otomatis menghitung (Total Skor / Jumlah Soal) menjadi Nilai Akhir 1-100</h6>
                    </div>
                    <button type="submit" class="btn btn-info text-white fw-bold px-5 py-2 shadow fs-5 rounded-pill">
                        <i class="fas fa-save me-2"></i> Simpan & Update Nilai Akhir
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
