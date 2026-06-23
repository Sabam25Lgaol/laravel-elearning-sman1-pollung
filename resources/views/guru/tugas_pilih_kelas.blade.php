@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body">
                <h4 class="text-warning mb-1 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Pilih Kelas: Kelola Tugas</h4>
                <p class="text-muted mb-0">Silakan pilih mata pelajaran di bawah ini untuk membuat tugas baru atau mengoreksi jawaban siswa.</p>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white border-top border-warning border-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3 text-center" style="min-width: 60px;">No</th>
                                <th class="py-3" style="min-width: 150px;">Kelas</th>
                                <th class="py-3" style="min-width: 250px;">Mata Pelajaran</th>
                                <th class="py-3 text-center" style="min-width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelajarans as $index => $p)
                            <tr>
                                <td class="px-4 text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 rounded-pill fw-bold border border-warning border-opacity-25">
                                        <i class="fas fa-door-open me-1 text-warning"></i> {{ $p->dataKelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">{{ $p->nama_pelajaran }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('guru.tugas', $p->id) }}" class="btn btn-outline-warning text-dark btn-sm fw-bold rounded-pill px-4 shadow-sm transition-all">
                                        Kelola Tugas <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-chalkboard-teacher fa-3x opacity-25 mb-3"></i>
                                    <h6 class="fw-bold">Belum Ada Kelas</h6>
                                    <small>Anda belum ditugaskan untuk mengajar oleh Admin.</small>
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
