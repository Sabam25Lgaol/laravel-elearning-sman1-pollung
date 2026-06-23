@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0 border-start border-danger border-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-danger mb-1 fw-bold"><i class="fas fa-calendar-check me-2"></i>Kelola Absensi: {{ $pelajaran->nama_pelajaran }}</h4>
                    <p class="text-muted mb-0">Catat kehadiran harian dan pantau rekapitulasi per pertemuan.</p>
                </div>
                <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm fw-bold border-0" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="col-md-12">
        <ul class="nav nav-pills mb-4 bg-white shadow-sm rounded-pill p-2 border border-danger border-opacity-10 d-inline-flex overflow-auto text-nowrap" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill fw-bold px-4" id="pills-harian-tab" data-bs-toggle="pill" data-bs-target="#pills-harian" type="button" role="tab" aria-controls="pills-harian" aria-selected="true">
                    <i class="fas fa-edit me-1"></i> Isi Absensi Harian
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold px-4 text-dark" id="pills-rekap-tab" data-bs-toggle="pill" data-bs-target="#pills-rekap" type="button" role="tab" aria-controls="pills-rekap" aria-selected="false">
                    <i class="fas fa-list-ol me-1 text-danger"></i> Daftar Pertemuan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-harian" role="tabpanel" aria-labelledby="pills-harian-tab" tabindex="0">
                <div class="card shadow-sm border-0 bg-light mb-4 rounded-4">
                    <div class="card-body py-3">
                        <form action="{{ route('guru.absensi', $pelajaran->id) }}" method="GET" class="d-flex align-items-center flex-wrap gap-3">
                            <label class="fw-bold text-dark mb-0"><i class="fas fa-calendar-alt me-1 text-danger"></i> Pilih Tanggal Absensi:</label>
                            <input type="date" name="tanggal" class="form-control w-auto border-danger shadow-sm fw-bold text-danger rounded-pill px-3" value="{{ $tanggal }}" onchange="this.form.submit()">

                            @if(isset($riwayat_pertemuan[$tanggal]))
                                <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">Pertemuan Ke-{{ $riwayat_pertemuan[$tanggal]['pertemuan'] }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm">Pertemuan Baru</span>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0 border-top border-danger border-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold text-dark d-flex justify-content-between align-items-center py-3">
                        <span><i class="fas fa-users me-1 text-danger"></i> Daftar Siswa Kelas Ini</span>
                        <span class="badge bg-danger fs-6 shadow-sm rounded-pill px-3"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</span>
                    </div>
                    <div class="card-body p-0">
                        <form action="{{ route('guru.absensi.store', $pelajaran->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 text-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center px-3" style="min-width: 60px;">No</th>
                                            <th style="min-width: 250px;">Nama Siswa</th>
                                            <th style="min-width: 150px;">NIS / Nomor Induk</th>
                                            <th class="text-center" style="min-width: 350px;">Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswas as $index => $siswa)
                                        @php
                                            $status_saat_ini = isset($absensi_hari_ini[$siswa->id]) ? $absensi_hari_ini[$siswa->id]->status : 'Alfa';
                                        @endphp
                                        <tr>
                                            <td class="text-center px-3 text-muted fw-bold">{{ $index + 1 }}</td>
                                            <td class="fw-bold text-primary">{{ $siswa->name }}</td>
                                            <td class="text-muted">{{ $siswa->nomor_induk ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group shadow-sm" role="group">
                                                    <input type="radio" class="btn-check" name="status[{{ $siswa->id }}]" id="hadir_{{ $siswa->id }}" value="Hadir" {{ $status_saat_ini == 'Hadir' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success fw-bold px-3" for="hadir_{{ $siswa->id }}">Hadir</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $siswa->id }}]" id="izin_{{ $siswa->id }}" value="Izin" {{ $status_saat_ini == 'Izin' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning fw-bold px-3 text-dark" for="izin_{{ $siswa->id }}">Izin</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $siswa->id }}]" id="sakit_{{ $siswa->id }}" value="Sakit" {{ $status_saat_ini == 'Sakit' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info fw-bold px-3 text-dark" for="sakit_{{ $siswa->id }}">Sakit</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $siswa->id }}]" id="alfa_{{ $siswa->id }}" value="Alfa" {{ $status_saat_ini == 'Alfa' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger fw-bold px-3" for="alfa_{{ $siswa->id }}">Alfa</label>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                                <h6>Belum ada siswa di kelas ini.</h6>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light text-end p-3 border-top-0">
                                <button type="submit" class="btn btn-danger fw-bold px-4 rounded-pill shadow-sm transition-all">
                                    <i class="fas fa-save me-1"></i> Simpan Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-rekap" role="tabpanel" aria-labelledby="pills-rekap-tab" tabindex="0">
                <div class="card shadow-sm border-0 border-top border-primary border-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white fw-bold text-dark py-3">
                        <i class="fas fa-list-ol me-1 text-primary"></i> Daftar Pertemuan Kelas
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center px-4" style="width: 5%;">No</th>
                                        <th>Pertemuan Ke-</th>
                                        <th>Tanggal Absensi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayat_pertemuan as $tgl => $riwayat)
                                    <tr>
                                        <td class="text-center px-4 text-muted fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold border border-primary border-opacity-25">
                                                Pertemuan {{ $riwayat['pertemuan'] }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark">{{ \Carbon\Carbon::parse($tgl)->format('d F Y') }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $riwayat['pertemuan'] }}">
                                                <i class="fas fa-search me-1"></i> Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                            <h6>Belum ada riwayat pertemuan.</h6>
                                            <small>Silakan isi absensi harian pada tab sebelah.</small>
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
    </div>
</div>

@foreach($riwayat_pertemuan as $tgl => $riwayat)
<div class="modal fade" id="modalDetail{{ $riwayat['pertemuan'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title fw-bold text-white"><i class="fas fa-list me-2"></i>Detail Absensi Pertemuan {{ $riwayat['pertemuan'] }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-3 border-bottom text-center">
                    <span class="text-muted fw-bold"><i class="far fa-calendar-alt me-1"></i> Tanggal: {{ \Carbon\Carbon::parse($tgl)->format('d F Y') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 text-center" style="width: 5%">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $idx => $siswa)
                            @php
                                // Ambil status spesifik siswa ini pada pertemuan ini
                                $status_siswa = isset($riwayat['detail'][$siswa->id]) ? $riwayat['detail'][$siswa->id]->status : 'Alfa';

                                // Tentukan warna badge berdasarkan status
                                $badge_class = 'bg-danger';
                                if($status_siswa == 'Hadir') $badge_class = 'bg-success';
                                if($status_siswa == 'Izin') $badge_class = 'bg-warning text-dark';
                                if($status_siswa == 'Sakit') $badge_class = 'bg-info text-dark';
                            @endphp
                            <tr>
                                <td class="px-4 text-center text-muted fw-bold">{{ $idx + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $siswa->name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badge_class }} px-4 py-2 rounded-pill shadow-sm">{{ $status_siswa }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
