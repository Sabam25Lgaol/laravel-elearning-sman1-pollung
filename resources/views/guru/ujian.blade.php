@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold text-primary"><i class="fas fa-flask me-2"></i>Kelola Ujian / Quiz: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Buat jadwal ujian, tentukan durasi, dan masukkan soal-soal pilihan ganda.</p>
                </div>
                <a href="{{ route('guru.pelajaran.show', $pelajaran->id) }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Ruang Kelas
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm">
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
        <div class="card shadow-sm border-0">
            <div class="card-header text-white fw-bold bg-primary">
                <i class="fas fa-plus-circle me-1"></i> Buat Wadah Ujian Baru
            </div>
            <div class="card-body">
                <form action="{{ route('guru.ujian.store', $pelajaran->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" name="judul_ujian" class="form-control" placeholder="Contoh: UTS Ganjil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Durasi (Menit) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi" class="form-control" placeholder="Contoh: 60" min="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ujian Dibuka <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ujian Ditutup <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_selesai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Keterangan / Aturan Ujian</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Harap kerjakan dengan jujur..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary text-white w-100 fw-bold shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-header bg-white fw-bold text-dark">
                <i class="fas fa-list-alt me-1"></i> Daftar Ujian yang Telah Dibuat
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Judul Ujian</th>
                                <th>Jadwal Pelaksanaan</th>
                                <th>Durasi</th>
                                <th>Aksi (Kelola Soal)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ujians as $u)
                            <tr>
                                <td class="px-4 fw-bold text-primary">{{ $u->judul_ujian }}</td>
                                <td class="small">
                                    <span class="text-success fw-bold"><i class="fas fa-play-circle me-1"></i>Mulai:</span> {{ \Carbon\Carbon::parse($u->waktu_mulai)->format('d M Y, H:i') }}<br>
                                    <span class="text-danger fw-bold"><i class="fas fa-stop-circle me-1"></i>Tutup:</span> {{ \Carbon\Carbon::parse($u->waktu_selesai)->format('d M Y, H:i') }}
                                </td>
                                <td><span class="badge bg-secondary shadow-sm"><i class="fas fa-clock me-1"></i>{{ $u->durasi }} Menit</span></td>

                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('guru.ujian.nilai', $u->id) }}" class="btn btn-sm btn-success fw-bold shadow-sm">
                                            <i class="fas fa-clipboard-check me-1"></i> Lihat Nilai
                                        </a>

                                        <a href="{{ route('guru.ujian.soal', $u->id) }}" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                            <i class="fas fa-tasks me-1"></i> Kelola Soal
                                        </a>

                                        <button type="button" class="btn btn-sm btn-warning fw-bold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#editUjian{{ $u->id }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('guru.ujian.delete', $u->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm" onclick="return confirm('YAKIN HAPUS UJIAN INI? Semua soal dan nilai siswa akan ikut terhapus permanen!')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
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
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-pen me-1"></i> Edit Detail Ujian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guru.ujian.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Judul Ujian *</label>
                        <input type="text" name="judul_ujian" class="form-control" value="{{ $u->judul_ujian }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Durasi (Menit) *</label>
                        <input type="number" name="durasi" class="form-control" value="{{ $u->durasi }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Dibuka *</label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($u->waktu_mulai)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Waktu Ditutup *</label>
                        <input type="datetime-local" name="waktu_selesai" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($u->waktu_selesai)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Keterangan / Aturan Ujian</label>
                        <textarea name="deskripsi" class="form-control" rows="2">{{ $u->deskripsi }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
