@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>Koreksi Jawaban: {{ $siswa->name }}</h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-file-alt me-1"></i> Ujian: <strong>{{ $ujian->judul_ujian }}</strong> |
                        <i class="fas fa-id-badge me-1"></i> NIS/Induk: <strong>{{ $siswa->nomor_induk ?? '-' }}</strong>
                    </p>
                </div>
                <a href="{{ route('guru.ujian.nilai', $ujian->id) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
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

            <div class="card shadow-sm border-0 mb-4 border-start border-4 border-{{ $soal->jenis_soal == 'essay' ? 'warning' : 'primary' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h5 class="fw-bold text-dark mb-0">Soal No. {{ $index + 1 }}</h5>
                        @if($soal->jenis_soal == 'essay')
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6 shadow-sm"><i class="fas fa-pen-nib me-1"></i> Essay (Penilaian Manual)</span>
                        @else
                            <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm"><i class="fas fa-robot me-1"></i> Pilihan Ganda (Auto)</span>
                        @endif
                    </div>

                    <p class="fs-5 text-dark">{{ $soal->pertanyaan }}</p>

                    @if($soal->gambar_soal)
                        <div class="mb-3">
                            <img src="{{ asset('uploads/soal/' . $soal->gambar_soal) }}" alt="Gambar Soal" class="img-fluid rounded shadow-sm border koreksi-image">
                        </div>
                    @endif

                    <div class="bg-light p-3 rounded border mb-3">
                        <h6 class="fw-bold text-muted mb-2"><i class="fas fa-comment-dots me-1"></i> Jawaban Siswa:</h6>

                        @if(!$jawaban_siswa || empty($teks_jawaban))
                            <span class="text-danger fst-italic">Siswa tidak menjawab / mengosongkan soal ini.</span>
                        @else
                            @if($soal->jenis_soal == 'pilihan_ganda')
                                <p class="mb-1 fs-5 fw-bold text-primary">{{ $teks_jawaban }}</p>
                                <p class="mb-0 small text-muted">Kunci Jawaban Seharusnya: <strong>{{ $soal->kunci_jawaban }}</strong></p>
                            @else
                                <p class="mb-0 fs-6 text-dark jawaban-essay">{{ $teks_jawaban }}</p>
                            @endif
                        @endif
                    </div>

                    <div class="d-flex justify-content-end align-items-center bg-white p-2 rounded">
                        <label class="fw-bold me-3 text-dark fs-6">Skor Poin Soal Ini (0-100):</label>
                        @if($soal->jenis_soal == 'pilihan_ganda')
                            <input type="number" class="form-control text-center fw-bold bg-light nilai-input {{ $skor_saat_ini > 0 ? 'text-success border-success' : 'text-danger border-danger' }}" value="{{ $skor_saat_ini }}" readonly>
                        @else
                            <input type="number" name="skor[{{ $soal->id }}]" class="form-control text-center fw-bold border-warning bg-warning bg-opacity-10 shadow-sm nilai-input" min="0" max="100" value="{{ $skor_saat_ini }}" required>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach

            <div class="card shadow-lg border-0 sticky-bottom mb-4 bg-white floating-footer">
                <div class="card-body d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                    <div>
                        <h6 class="fw-bold text-muted mb-0"><i class="fas fa-calculator me-1"></i> Sistem otomatis menghitung (Total Skor / Jumlah Soal) menjadi Nilai Akhir 1-100</h6>
                    </div>
                    <button type="submit" class="btn btn-primary fw-bold px-5 py-2 shadow fs-5">
                        <i class="fas fa-save me-2"></i> Simpan & Update Nilai Akhir
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
