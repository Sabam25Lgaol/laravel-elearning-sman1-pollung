@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-info"><i class="fas fa-flask me-2"></i>Kelola Ujian / Quiz: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Buat jadwal ujian, tentukan durasi, dan masukkan soal-soal pilihan ganda.</p>
                </div>
                <a href="{{ route('guru.pelajaran.show', $pelajaran->id) }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Ruang Kelas
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
            <i class="fas fa-exclamation-triangle me-1"></i> Terjadi kesalahan:
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header text-white fw-bold bg-info border-0 py-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <i class="fas fa-plus-circle me-1"></i> Buat Wadah Ujian Baru
            </div>
            <div class="card-body p-4 bg-light">
                <form action="{{ route('guru.ujian.store', $pelajaran->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="judul_ujian" class="form-control shadow-sm border-0" placeholder="Contoh: UTS Ganjil" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Durasi (Menit) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi" class="form-control shadow-sm border-0" placeholder="Contoh: 60" min="5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ujian Dibuka <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control shadow-sm border-0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ujian Ditutup <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_selesai" class="form-control shadow-sm border-0" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Keterangan / Aturan Ujian</label>
                        <textarea name="deskripsi" class="form-control shadow-sm border-0 rounded-3" rows="3" placeholder="Harap kerjakan dengan jujur..."></textarea>
                    </div>

                    <div class="mb-4 p-3 bg-white rounded-4 border border-info border-opacity-25 shadow-sm">
                        <div class="fw-bold text-info mb-2">
                            <i class="fas fa-random me-1"></i> Pengacakan Ujian
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="acak_soal" value="1" id="acakSoal">
                            <label class="form-check-label fw-bold small" for="acakSoal">Acak urutan soal setiap siswa</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="acak_jawaban" value="1" id="acakJawaban">
                            <label class="form-check-label fw-bold small" for="acakJawaban">Acak pilihan jawaban A/B/C/D</label>
                        </div>
                        <small class="text-muted d-block mt-2">Jika aktif, tampilan soal/jawaban tiap siswa dibuat berbeda tetapi penilaian tetap otomatis.</small>
                    </div>

                    <button type="submit" class="btn btn-info text-white w-100 fw-bold shadow-sm rounded-pill py-2">
                        <i class="fas fa-save me-1"></i> Simpan Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 border-top border-info border-4 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold text-dark py-3">
                <i class="fas fa-list-alt me-1 text-info"></i> Daftar Ujian yang Telah Dibuat
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3">Judul Ujian</th>
                                <th class="py-3">Jadwal Pelaksanaan</th>
                                <th class="py-3 text-center">Durasi</th>
                                <th class="py-3 text-center">Aksi (Kelola Soal)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ujians as $u)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold text-info text-break">{{ $u->judul_ujian }}</div>
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        @if($u->acak_soal)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">
                                                <i class="fas fa-random me-1"></i>Soal Acak
                                            </span>
                                        @endif
                                        @if($u->acak_jawaban)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">
                                                <i class="fas fa-list-ul me-1"></i>Jawaban Acak
                                            </span>
                                        @endif
                                        @if(!$u->acak_soal && !$u->acak_jawaban)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill">Tidak Diacak</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="small">
                                    <div class="d-flex flex-column gap-1">
                                        <span class="text-success fw-bold"><i class="fas fa-play-circle me-1"></i>Mulai: <span class="text-dark fw-normal">{{ \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y, H:i') }}</span></span>
                                        <span class="text-danger fw-bold"><i class="fas fa-stop-circle me-1"></i>Tutup: <span class="text-dark fw-normal">{{ \Carbon\Carbon::parse($u->waktu_selesai)->format('d M Y, H:i') }}</span></span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary shadow-sm px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>{{ $u->durasi }} Menit</span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('guru.ujian.nilai', $u->id) }}" class="btn btn-sm btn-outline-info fw-bold shadow-sm rounded-pill px-3">
                                            <i class="fas fa-clipboard-check me-1"></i> Lihat Nilai
                                        </a>

                                        <a href="{{ route('guru.ujian.soal', $u->id) }}" class="btn btn-sm btn-info text-white fw-bold shadow-sm rounded-pill px-3">
                                            <i class="fas fa-tasks me-1"></i> Kelola Soal
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-info fw-bold shadow-sm rounded-pill px-3 guru-row-action" data-bs-toggle="modal" data-bs-target="#editUjian{{ $u->id }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('guru.ujian.delete', $u->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold shadow-sm rounded-pill px-3 guru-row-action" onclick="return confirm('YAKIN HAPUS UJIAN INI? Semua soal dan nilai siswa akan ikut terhapus permanen!')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-laptop-code fa-3x mb-3 opacity-25"></i>
                                    <h6>Belum ada ujian yang dibuat.</h6>
                                    <small>Gunakan form di sebelah kiri untuk membuat jadwal ujian baru.</small>
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

@foreach($ujians as $u)
<div class="modal fade text-start" id="editUjian{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-info text-white border-0 py-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold"><i class="fas fa-pen me-2"></i>Edit Detail Ujian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guru.ujian.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="judul_ujian" class="form-control shadow-sm border-0" value="{{ $u->judul_ujian }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Durasi (Menit) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi" class="form-control shadow-sm border-0" value="{{ $u->durasi }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Dibuka <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control shadow-sm border-0" value="{{ date('Y-m-d\TH:i', strtotime($u->waktu_mulai)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ditutup <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_selesai" class="form-control shadow-sm border-0" value="{{ date('Y-m-d\TH:i', strtotime($u->waktu_selesai)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Keterangan / Aturan Ujian</label>
                        <textarea name="deskripsi" class="form-control shadow-sm border-0 rounded-3" rows="3">{{ $u->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3 p-3 bg-white rounded-4 border border-info border-opacity-25 shadow-sm">
                        <div class="fw-bold text-info mb-2">
                            <i class="fas fa-random me-1"></i> Pengacakan Ujian
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="acak_soal" value="1" id="editAcakSoal{{ $u->id }}" {{ $u->acak_soal ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="editAcakSoal{{ $u->id }}">Acak urutan soal setiap siswa</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="acak_jawaban" value="1" id="editAcakJawaban{{ $u->id }}" {{ $u->acak_jawaban ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="editAcakJawaban{{ $u->id }}">Acak pilihan jawaban A/B/C/D</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
