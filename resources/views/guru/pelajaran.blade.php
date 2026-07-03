@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-primary mb-1 fw-bold">📚 Kelola Mata Pelajaran</h4>
                    <p class="text-muted mb-0">Berikut adalah daftar mata pelajaran beserta kelas yang ditugaskan kepada Anda.</p>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary btn-sm fw-bold">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-primary border-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Daftar Pelajaran Saya</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="20%">Nama Pelajaran</th>
                                <th width="15%">Kelas</th>
                                <th width="30%">Deskripsi</th>
                                <th width="30%" class="text-center">Aksi (Ruang Kelas)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelajarans as $index => $pelajaran)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold text-primary fs-6">{{ $pelajaran->nama_pelajaran }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $pelajaran->kelas ?? 'Belum Diatur' }}</span>
                                </td>
                                <td class="text-muted small">{{ $pelajaran->deskripsi ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('guru.materi', $pelajaran->id) }}" class="btn btn-sm btn-info text-white fw-bold">📖 Materi</a>
                                        <a href="{{ route('guru.tugas', $pelajaran->id) }}" class="btn btn-sm btn-warning text-dark fw-bold">📝 Tugas</a>
                                        <a href="{{ route('guru.absensi', $pelajaran->id) }}" class="btn btn-sm btn-danger text-white fw-bold">📅 Absen</a>
                                        <a href="{{ route('guru.ujian', $pelajaran->id) }}" class="btn btn-sm btn-primary text-white fw-bold">🧪 Ujian</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <h6>Belum ada mata pelajaran yang ditugaskan kepada Anda oleh Admin.</h6>
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
