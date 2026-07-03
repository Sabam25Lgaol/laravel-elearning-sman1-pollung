@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-0 bg-gradient-admin text-white shadow-sm rounded-4 admin-hero">
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-2 fw-bold"><i class="fas fa-satellite-dish me-2"></i>Pusat Komando Administrator</h3>
                    <p class="mb-0 fs-6 opacity-75">Ringkasan statistik dan direktori pengguna E-Learning SMAN 1 Pollung.</p>
                </div>
                <div class="d-none d-md-block opacity-50">
                    <i class="fas fa-server fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- CLEAN CODE: Mengganti style inline dengan class border-start-5-* -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 card-stat-admin bg-white admin-stat-card stat-student">
            <div class="card-body p-4">
                <i class="fas fa-user-graduate text-info stat-icon-bg"></i>
                <h6 class="text-muted fw-bold mb-2 text-uppercase letter-spacing-1">Total Siswa Terdaftar</h6>
                <h2 class="fw-bold text-dark mb-0 fs-1">{{ $total_siswa }} <span class="fs-6 text-muted fw-normal">Siswa</span></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 card-stat-admin bg-white admin-stat-card stat-teacher">
            <div class="card-body p-4">
                <i class="fas fa-chalkboard-teacher text-success stat-icon-bg"></i>
                <h6 class="text-muted fw-bold mb-2 text-uppercase letter-spacing-1">Total Guru Pengajar</h6>
                <h2 class="fw-bold text-dark mb-0 fs-1">{{ $total_guru }} <span class="fs-6 text-muted fw-normal">Guru</span></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 card-stat-admin bg-white admin-stat-card stat-admin">
            <div class="card-body p-4">
                <i class="fas fa-user-shield text-danger stat-icon-bg"></i>
                <h6 class="text-muted fw-bold mb-2 text-uppercase letter-spacing-1">Total Administrator</h6>
                <h2 class="fw-bold text-dark mb-0 fs-1">{{ $total_admin }} <span class="fs-6 text-muted fw-normal">Admin</span></h2>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-address-book text-info me-2"></i>Direktori Seluruh Pengguna</h5>
                <a href="{{ route('admin.pengguna') }}" class="btn btn-info text-white fw-bold shadow-sm rounded-pill px-4">
                    <i class="fas fa-users-cog me-1"></i> Kelola Pengguna
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4 py-3">Nama Pengguna</th>
                                <th class="py-3">Email Akun</th>
                                <th class="py-3">Nomor Induk</th>
                                <th class="py-3">Jabatan / Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $roleColor = $u->hasRole('Admin') ? 'danger' : ($u->hasRole('Guru') ? 'success' : 'primary');
                                        @endphp
                                        <div class="avatar-circle bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-25">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                    </div>
                                </td>
                                <td><span class="text-muted small"><i class="fas fa-envelope text-secondary me-1"></i>{{ $u->email }}</span></td>
                                <td>
                                    @if($u->nomor_induk)
                                        <span class="fw-bold text-secondary">{{ $u->nomor_induk }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">Belum diisi</span>
                                    @endif
                                </td>
                                <td>
                                    @if($u->hasRole('Admin'))
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-1"><i class="fas fa-shield-alt me-1"></i> Admin</span>
                                    @elseif($u->hasRole('Guru'))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1"><i class="fas fa-chalkboard-teacher me-1"></i> Guru</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1"><i class="fas fa-user-graduate me-1"></i> Siswa</span>
                                    @endif
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
@endsection
