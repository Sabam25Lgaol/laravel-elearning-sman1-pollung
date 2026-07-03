@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 text-info fw-bold"><i class="fas fa-users me-2"></i>Kelola Siswa: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Guru Pengajar: <strong>{{ $pelajaran->guru->name ?? 'Belum Ditentukan' }}</strong></p>
                </div>
                <a href="{{ route('admin.pelajaran') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3 px-4">
                <span><i class="fas fa-user-check text-info me-2"></i>Centang nama siswa yang berhak masuk ke kelas ini</span>
                <span class="badge bg-info text-white rounded-pill px-3 py-2">{{ $semuaSiswa->count() }} Total Siswa</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pelajaran.siswa.sync', $pelajaran->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        @forelse($semuaSiswa as $siswa)
                            <div class="col-md-3 mb-3">
                                <div class="form-check admin-student-check border p-3 rounded-3 h-100 {{ in_array($siswa->id, $siswaTerdaftar) ? 'bg-light border-info' : '' }}">
                                    <input class="form-check-input ms-1" type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" id="siswa_{{ $siswa->id }}"
                                    {{ in_array($siswa->id, $siswaTerdaftar) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100 ms-2" style="cursor: pointer;" for="siswa_{{ $siswa->id }}">
                                        <span class="fw-bold text-dark">{{ $siswa->name }}</span>
                                        <div class="small text-muted">{{ $siswa->email }}</div>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="text-muted mb-0">Belum ada data siswa di sistem.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($semuaSiswa->count() > 0)
                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-info text-white fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Daftar Siswa
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
