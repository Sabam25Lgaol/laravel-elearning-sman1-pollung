@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 border-top border-dark border-4">
            <div class="card-header bg-dark text-white fw-bold">
                Buat Pelajaran Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pelajaran.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Nama Pelajaran *</label>
                        <input type="text" name="nama_pelajaran" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Tugaskan ke Guru *</label>
                        <select name="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Pilih Kelas (Otomatis Masuk) *</label>
                        <select name="kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->nama_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Simpan Pelajaran</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if(session('success')) <div class="alert alert-success fw-bold">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="alert alert-danger fw-bold">Ada kesalahan input data.</div> @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3 py-3">Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Guru Pengampu</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelajarans as $p)
                            <tr>
                                <td class="px-3 fw-bold text-dark">{{ $p->nama_pelajaran }}</td>
                                <td><span class="badge bg-info text-dark">{{ $p->kelas ?? '-' }}</span></td>
                                <td class="text-muted">{{ $p->guru->name ?? 'Guru Tidak Ditemukan' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#editPelajaran{{ $p->id }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.pelajaran.delete', $p->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Yakin hapus pelajaran ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <div class="modal fade text-start" id="editPelajaran{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title fw-bold text-dark">Edit: {{ $p->nama_pelajaran }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.pelajaran.update', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Nama Pelajaran *</label>
                                                            <input type="text" name="nama_pelajaran" class="form-control" value="{{ $p->nama_pelajaran }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Ganti Guru *</label>
                                                            <select name="guru_id" class="form-select" required>
                                                                @foreach($gurus as $guru)
                                                                    <option value="{{ $guru->id }}" {{ $p->guru_id == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Pindah Kelas *</label>
                                                            <select name="kelas" class="form-select" required>
                                                                @foreach($kelasList as $kelas)
                                                                    <option value="{{ $kelas->nama_kelas }}" {{ $p->kelas == $kelas->nama_kelas ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-danger">Peringatan: Mengganti kelas akan mereset ulang seluruh siswa yang terdaftar ke pelajaran ini!</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-dark">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $p->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada mata pelajaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
