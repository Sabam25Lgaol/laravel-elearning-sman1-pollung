@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-info mb-1 fw-bold"><i class="fas fa-chalkboard me-2"></i>Master Data Kelas</h4>
                    <p class="text-muted mb-0">Kelola daftar kelas yang tersedia di SMAN 1 Pollung.</p>
                </div>
                <button type="button" class="btn btn-info text-white fw-bold shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#tambahKelas">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Kelas
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-md-12 mb-3">
            <div class="alert alert-success fw-bold shadow-sm rounded-3 border-0">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="col-md-12 mb-3">
            <div class="alert alert-danger fw-bold shadow-sm rounded-3 border-0">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-triangle me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden border-top border-info border-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3" width="5%">No</th>
                                <th width="25%">Nama Kelas</th>
                                <th>Deskripsi / Wali Kelas</th>
                                <th class="text-center" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas as $index => $k)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge admin-class-badge px-3 py-2 rounded-pill fw-bold">
                                        <i class="fas fa-door-open me-1"></i> {{ $k->nama_kelas }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ $k->deskripsi ?? 'Tidak ada deskripsi' }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        <a href="{{ route('admin.kelas.siswa', $k->nama_kelas) }}" class="btn btn-sm btn-info text-white fw-bold rounded-pill px-3">
                                            <i class="fas fa-users"></i> Siswa
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-info fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editKelas{{ $k->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <form action="{{ route('admin.kelas.delete', $k->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus kelas {{ $k->nama_kelas }}? Ini mungkin memengaruhi data siswa!')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <div class="modal fade text-start" id="editKelas{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white border-0">
                                                    <h5 class="modal-title fw-bold">Edit Kelas: {{ $k->nama_kelas }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.kelas.update', $k->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body bg-light p-4">
                                                        <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                                                            <label class="form-label fw-bold small text-dark">Nama Kelas *</label>
                                                            <input type="text" name="nama_kelas" class="form-control border-0 bg-light" value="{{ $k->nama_kelas }}" required>
                                                        </div>
                                                        <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                                                            <label class="form-label fw-bold small text-dark">Deskripsi / Wali Kelas</label>
                                                            <textarea name="deskripsi" class="form-control border-0 bg-light" rows="3" placeholder="Opsional...">{{ $k->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-white border-0">
                                                        <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data kelas. Silakan tambahkan!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> <div class="modal fade" id="tambahKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold">Tambah Kelas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start bg-light p-4">
                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                        <label class="form-label fw-bold small text-dark">Nama Kelas *</label>
                        <input type="text" name="nama_kelas" class="form-control border-0 bg-light" placeholder="Contoh: 10 IPA 1" required>
                    </div>
                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                        <label class="form-label fw-bold small text-dark">Deskripsi / Wali Kelas</label>
                        <textarea name="deskripsi" class="form-control border-0 bg-light" rows="3" placeholder="Opsional (misal: Wali Kelas Bapak Sabam)"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0">
                    <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-4">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
