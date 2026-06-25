@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-primary"><i class="fas fa-list-ol me-2"></i>Kelola Soal: {{ $ujian->judul_ujian }}</h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-book me-1"></i> Pelajaran: <strong>{{ $ujian->pelajaran->nama_pelajaran }}</strong> |
                        <i class="fas fa-clock me-1"></i> Durasi: <strong>{{ $ujian->durasi }} Menit</strong>
                    </p>
                </div>
                <a href="{{ route('guru.ujian', $ujian->pelajaran_id) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Ujian
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="col-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm">
            <i class="fas fa-exclamation-triangle me-1"></i> Gagal menyimpan soal. Detail kesalahan:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="col-12 col-lg-5 mb-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fas fa-plus-circle me-1"></i> Tambah Soal Baru
            </div>
            <div class="card-body">
                <form action="{{ route('guru.ujian.soal.store', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-dark">Jenis Soal <span class="text-danger">*</span></label>
                            <select name="jenis_soal" id="jenis_soal" class="form-select border-primary fw-bold text-primary shadow-sm">
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Upload Gambar Pendukung (Opsional)</label>
                            <input type="file" name="gambar_soal" class="form-control shadow-sm" accept="image/png, image/jpeg, image/jpg">
                            <small class="text-muted">Format: JPG/PNG, Maksimal: 2MB.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea name="pertanyaan" class="form-control form-control-lg shadow-sm" rows="3" placeholder="Ketik soal/pertanyaan di sini..." required></textarea>
                    </div>

                    <div id="area_pilihan_ganda">
                        <hr class="text-muted">
                        <h6 class="fw-bold text-primary"><i class="fas fa-tasks me-1"></i> Opsi Jawaban Pilihan Ganda</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Pilihan A</label>
                                <input type="text" name="pilihan_a" id="opt_a" class="form-control" placeholder="Teks pilihan A">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Pilihan B</label>
                                <input type="text" name="pilihan_b" id="opt_b" class="form-control" placeholder="Teks pilihan B">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Pilihan C</label>
                                <input type="text" name="pilihan_c" id="opt_c" class="form-control" placeholder="Teks pilihan C">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Pilihan D</label>
                                <input type="text" name="pilihan_d" id="opt_d" class="form-control" placeholder="Teks pilihan D">
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded border-start border-success border-4 shadow-sm">
                            <label class="form-label fw-bold text-success mb-2"><i class="fas fa-key me-1"></i> Kunci Jawaban (Bisa lebih dari 1):</label>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border-success" type="checkbox" name="kunci_jawaban[]" value="A" id="kunciA">
                                <label class="form-check-label fw-bold" for="kunciA">A</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border-success" type="checkbox" name="kunci_jawaban[]" value="B" id="kunciB">
                                <label class="form-check-label fw-bold" for="kunciB">B</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border-success" type="checkbox" name="kunci_jawaban[]" value="C" id="kunciC">
                                <label class="form-check-label fw-bold" for="kunciC">C</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border-success" type="checkbox" name="kunci_jawaban[]" value="D" id="kunciD">
                                <label class="form-check-label fw-bold" for="kunciD">D</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm w-100">
                            <i class="fas fa-save me-1"></i> Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
            <i class="fas fa-layer-group me-2 text-secondary"></i>Daftar Soal Tersimpan
            <span class="badge bg-primary rounded-pill ms-2">{{ $soals->count() }} Soal</span>
        </h5>

        @forelse($soals as $index => $soal)
        <div class="card shadow-sm border-0 mb-3 border-start border-4 border-{{ $soal->jenis_soal == 'essay' ? 'warning' : 'primary' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <p class="fw-bold mb-0 fs-5 text-dark">
                        <span class="me-2 text-muted">{{ $index + 1 }}.</span> {{ $soal->pertanyaan }}

                        @if($soal->jenis_soal == 'essay')
                            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-pen-nib me-1"></i>Essay</span>
                        @else
                            <span class="badge bg-primary ms-2"><i class="fas fa-list-ul me-1"></i>Pilihan Ganda</span>
                        @endif
                    </p>
                    <form action="{{ route('guru.ujian.soal.delete', $soal->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold" onclick="return confirm('Yakin ingin menghapus soal ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>

                @if($soal->gambar_soal)
                    <div class="mb-3 mt-2 ps-4">
                        <img src="{{ asset('uploads/soal/' . $soal->gambar_soal) }}" alt="Gambar Soal" class="img-fluid rounded shadow-sm border soal-image">
                    </div>
                @endif

                @if($soal->jenis_soal == 'pilihan_ganda')
                    @php
                        $kunci = explode(',', $soal->kunci_jawaban ?? '');
                    @endphp
                    <div class="row ps-4 mt-3">
                        <div class="col-md-6 mb-2">
                            <div class="p-2 rounded {{ in_array('A', $kunci) ? 'bg-success bg-opacity-10 border border-success fw-bold text-success' : 'text-muted' }}">
                                A. {{ $soal->pilihan_a }} {!! in_array('A', $kunci) ? '<i class="fas fa-check-circle float-end mt-1"></i>' : '' !!}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="p-2 rounded {{ in_array('B', $kunci) ? 'bg-success bg-opacity-10 border border-success fw-bold text-success' : 'text-muted' }}">
                                B. {{ $soal->pilihan_b }} {!! in_array('B', $kunci) ? '<i class="fas fa-check-circle float-end mt-1"></i>' : '' !!}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="p-2 rounded {{ in_array('C', $kunci) ? 'bg-success bg-opacity-10 border border-success fw-bold text-success' : 'text-muted' }}">
                                C. {{ $soal->pilihan_c }} {!! in_array('C', $kunci) ? '<i class="fas fa-check-circle float-end mt-1"></i>' : '' !!}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="p-2 rounded {{ in_array('D', $kunci) ? 'bg-success bg-opacity-10 border border-success fw-bold text-success' : 'text-muted' }}">
                                D. {{ $soal->pilihan_d }} {!! in_array('D', $kunci) ? '<i class="fas fa-check-circle float-end mt-1"></i>' : '' !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-question-circle fa-3x mb-3 text-muted opacity-25"></i>
                <h6 class="text-muted fw-bold">Belum ada soal untuk ujian ini.</h6>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSoalDropdown = document.getElementById('jenis_soal');
        const areaPilihanGanda = document.getElementById('area_pilihan_ganda');
        const inputsPG = areaPilihanGanda.querySelectorAll('input[type="text"]');
        const checkboxesKunci = areaPilihanGanda.querySelectorAll('input[type="checkbox"]');

        function togglePilihanGanda() {
            if (jenisSoalDropdown.value === 'essay') {
                areaPilihanGanda.style.display = 'none';
                inputsPG.forEach(input => input.removeAttribute('required'));
                checkboxesKunci.forEach(cb => cb.removeAttribute('required'));
            } else {
                areaPilihanGanda.style.display = 'block';
                inputsPG.forEach(input => input.setAttribute('required', 'required'));
            }
        }

        togglePilihanGanda();
        jenisSoalDropdown.addEventListener('change', togglePilihanGanda);
    });
</script>
@endsection
