@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-info mb-1"><i class="fas fa-users me-2"></i>Daftar Siswa Kelas: {{ $kelas->nama_kelas }}</h4>
                    <p class="text-muted mb-0">Wali Kelas: {{ $kelas->deskripsi ?? 'Belum ditentukan' }}</p>
                </div>
                <a href="{{ route('admin.kelas') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary">Total Siswa: <span class="badge bg-primary">{{ $siswaDiKelas->count() }} Orang</span></h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3" width="5%">No</th>
                                <th>NISN / Nomor Induk</th>
                                <th>Nama Lengkap</th>
                                <th>Email Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaDiKelas as $index => $siswa)
                            <tr>
                                <td class="px-4 py-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold text-secondary">{{ $siswa->nomor_induk ?? '-' }}</td>
                                <td class="fw-bold text-dark">{{ $siswa->name }}</td>
                                <td class="text-muted">{{ $siswa->email }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fs-1 text-light mb-3 d-block"></i>
                                    Belum ada siswa yang dimasukkan ke kelas ini.<br>
                                    Silakan atur kelas siswa melalui menu <b>Kelola Pengguna</b>.
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
@endsection
