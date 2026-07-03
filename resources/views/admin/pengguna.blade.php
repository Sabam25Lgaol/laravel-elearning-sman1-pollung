@extends('layouts.app')

@section('content')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
    .modal-content {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
</style>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-0 bg-gradient-admin text-white shadow-sm rounded-4 admin-hero">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="fas fa-users-cog me-2"></i>Kelola Data Pengguna</h4>
                    <p class="mb-0 opacity-75"><i class="fas fa-info-circle me-1"></i> Tambah pengguna baru, lengkapi data NIS/NIP, dan tetapkan Hak Akses (Jabatan).</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light text-info fw-bold shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#tambahPengguna">
                        <i class="fas fa-user-plus me-1"></i> Tambah Akun Baru
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light fw-bold rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success fw-bold alert-dismissible fade show shadow-sm rounded-3">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold alert-dismissible fade show shadow-sm rounded-3">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list-ul text-info me-2"></i>Daftar Pengguna Terdaftar</h5>
                <span class="badge bg-info rounded-pill px-3 py-2">{{ $users->count() }} Pengguna</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3">Informasi Akun</th>
                                <th class="py-3">Jabatan</th>
                                <th class="py-3">NIS / NIP</th>
                                <th class="py-3">Kelas</th>
                                <th class="text-center py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <div class="small text-muted"><i class="fas fa-envelope text-secondary me-1"></i>{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->hasRole('Admin'))
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-1"><i class="fas fa-shield-alt me-1"></i> Admin</span>
                                    @elseif($user->hasRole('Guru'))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1"><i class="fas fa-chalkboard-teacher me-1"></i> Guru</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1"><i class="fas fa-user-graduate me-1"></i> Siswa</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-secondary">
                                    {{ $user->nomor_induk ?? '-' }}
                                </td>
                                <td>
                                    @if($user->hasRole('Siswa'))
                                        <span class="badge bg-info bg-opacity-10 text-dark border border-info rounded-pill px-3 py-1"><i class="fas fa-school me-1"></i> {{ $user->kelas ?? 'Belum diset' }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">Bukan Siswa</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <button type="button" class="btn btn-sm btn-outline-info fw-bold rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#editPengguna{{ $user->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <form action="{{ route('admin.pengguna.delete', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}? Semua data terkait akan ikut terhapus permanen!')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($users as $user)
<div class="modal fade text-start" id="editPengguna{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Lengkapi Data: {{ $user->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.ubah_role', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body bg-light p-4">
                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Email / Gmail Login *</label>
                        <input type="email" name="email" class="form-control border-0 bg-light" placeholder="email@gmail.com" value="{{ $user->email }}" required>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tetapkan Jabatan *</label>
                        <select name="role" class="form-select role-select border-0 bg-light" required>
                            <option value="Siswa" {{ $user->hasRole('Siswa') ? 'selected' : '' }}>Siswa</option>
                            <option value="Guru" {{ $user->hasRole('Guru') ? 'selected' : '' }}>Guru</option>
                            <option value="Admin" {{ $user->hasRole('Admin') ? 'selected' : '' }}>Admin (Administrator)</option>
                        </select>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nomor Induk (NIS/NIP) *</label>
                        <input type="text" name="nomor_induk" class="form-control border-0 bg-light" placeholder="Wajib diisi..." value="{{ $user->nomor_induk }}" required>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info kelas-container" style="display: {{ $user->hasRole('Siswa') ? 'block' : 'none' }};">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Kelas <span class="text-danger">*</span></label>
                        <select name="kelas" class="form-select kelas-select border-0 bg-light" {{ $user->hasRole('Siswa') ? 'required' : '' }}>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelasItem)
                                <option value="{{ $kelasItem->nama_kelas }}" {{ $user->kelas == $kelasItem->nama_kelas ? 'selected' : '' }}>
                                    {{ $kelasItem->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 pt-3 pb-4 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-bold px-4 shadow-sm rounded-pill"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="tambahPengguna" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pengguna.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start bg-light p-4">

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control border-0 bg-light" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Email (Akun Login) *</label>
                        <input type="email" name="email" class="form-control border-0 bg-light" placeholder="email@sekolah.com" required>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tetapkan Jabatan *</label>
                        <select name="role" class="form-select role-select border-0 bg-light" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Siswa">Siswa</option>
                            <option value="Guru">Guru</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nomor Induk (NIS/NIP) *</label>
                        <input type="text" name="nomor_induk" class="form-control border-0 bg-light" placeholder="Wajib diisi..." required>
                    </div>

                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border-start border-4 border-info kelas-container" style="display: none;">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Kelas <span class="text-danger">*</span></label>
                        <select name="kelas" class="form-select kelas-select border-0 bg-light">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelasItem)
                                <option value="{{ $kelasItem->nama_kelas }}">{{ $kelasItem->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer bg-white border-0 pt-3 pb-4 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white fw-bold px-4 shadow-sm rounded-pill"><i class="fas fa-user-check me-1"></i> Daftarkan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let roleSelects = document.querySelectorAll('.role-select');

        roleSelects.forEach(function(select) {
            // Jalankan saat load pertama kali untuk edit modal
            toggleKelasForm(select);

            // Jalankan saat ada perubahan dropdown
            select.addEventListener('change', function() {
                toggleKelasForm(this);
            });
        });

        function toggleKelasForm(selectElement) {
            let form = selectElement.closest('form');
            let kelasContainer = form.querySelector('.kelas-container');
            let kelasInput = form.querySelector('.kelas-select');

            if(selectElement.value === 'Siswa') {
                kelasContainer.style.display = 'block';
                kelasInput.setAttribute('required', 'required');
            } else {
                kelasContainer.style.display = 'none';
                kelasInput.removeAttribute('required');
                kelasInput.value = '';
            }
        }
    });
</script>
@endsection
