<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>E-Learning SMAN 1 Pollung</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guru.css') }}">
    <link rel="stylesheet" href="{{ asset('css/siswa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ujian.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="@if(Auth::check() && Auth::user()->hasRole('Guru')) guru-ui @elseif(Auth::check() && Auth::user()->hasRole('Admin')) admin-ui @elseif(Auth::check() && Auth::user()->hasRole('Siswa')) siswa-ui @endif">

    <div class="wrapper">

        <div class="offcanvas-lg offcanvas-start custom-sidebar shadow-sm" tabindex="-1" id="sidebarMenu">

            <div class="offcanvas-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-graduation-cap fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="letter-spacing: 0.5px;">E-Learning</h6>
                        <small class="text-primary fw-bold" style="font-size: 0.7rem;">SMAN 1 POLLUNG</small>
                    </div>
                </div>
                <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
            </div>

            <div class="p-4 text-center border-bottom">
                @php
                    $roleColor = Auth::user()->hasRole('Admin') ? 'danger' : (Auth::user()->hasRole('Guru') ? 'success' : 'primary');
                @endphp
                <div class="avatar-circle bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-50 mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h6 class="fw-bold text-dark mb-0">{{ Auth::user()->name }}</h6>
                <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} rounded-pill px-3 mt-1 fw-bold">
                    {{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}
                </span>
            </div>

            <div class="offcanvas-body d-flex flex-column p-0 py-3 overflow-y-auto">

                @hasrole('Admin')
                    <div class="sidebar-header-text">Pusat Komando</div>
                    <a href="/admin/dashboard" class="sidebar-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                    </a>

                    <div class="sidebar-header-text">Manajemen Akun</div>
                    <a href="{{ route('admin.pengguna') }}" class="sidebar-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i> Kelola Pengguna
                    </a>

                    <div class="sidebar-header-text">Data Master</div>
                    <a href="{{ route('admin.kelas') }}" class="sidebar-link {{ request()->is('admin/kelas*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i> Data Kelas
                    </a>
                    <a href="{{ route('admin.pelajaran') }}" class="sidebar-link {{ request()->is('admin/pelajaran*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i> Data Pelajaran
                    </a>
                @endhasrole

                @hasrole('Guru')
                    <div class="sidebar-header-text">Menu Utama</div>
                    <a href="/guru/dashboard" class="sidebar-link {{ request()->is('guru/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard Guru
                    </a>

                    <div class="sidebar-header-text">Manajemen Pembelajaran</div>
                    <a href="/guru/materi" class="sidebar-link {{ request()->is('guru/materi*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open"></i> Materi Pembelajaran
                    </a>
                    <a href="{{ route('guru.tugas.index') }}" class="sidebar-link {{ request()->is('guru/tugas*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Tugas Siswa
                    </a>
                    <a href="{{ route('guru.ujian.index') }}" class="sidebar-link {{ request()->is('guru/ujian*') ? 'active' : '' }}">
                        <i class="fas fa-laptop-code"></i> Ujian Online
                    </a>
                    <a href="{{ route('guru.absensi.index') }}" class="sidebar-link {{ request()->is('guru/absensi*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i> Absensi Kelas
                    </a>
                @endhasrole

                @hasrole('Siswa')
                    <div class="sidebar-header-text">Menu Utama</div>
                    <a href="/siswa/dashboard" class="sidebar-link {{ request()->is('siswa/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard Siswa
                    </a>

                    <div class="sidebar-header-text">Ruang Belajar</div>
                    <a href="{{ route('siswa.dashboard', ['mode' => 'materi']) }}#daftar-pelajaran" class="sidebar-link {{ request('mode') === 'materi' || request()->is('siswa/pelajaran/*/materi*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i> Materi Pembelajaran
                    </a>
                    <a href="{{ route('siswa.dashboard', ['mode' => 'tugas']) }}#daftar-pelajaran" class="sidebar-link {{ request('mode') === 'tugas' || request()->is('siswa/pelajaran/*/tugas*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Tugas Siswa
                    </a>
                    <a href="{{ route('siswa.dashboard', ['mode' => 'ujian']) }}#daftar-pelajaran" class="sidebar-link {{ request('mode') === 'ujian' || request()->is('siswa/pelajaran/*/ujian*') || request()->is('siswa/ujian*') ? 'active' : '' }}">
                        <i class="fas fa-laptop-code"></i> Ujian Online
                    </a>
                    <a href="{{ route('siswa.dashboard', ['mode' => 'absensi']) }}#daftar-pelajaran" class="sidebar-link {{ request('mode') === 'absensi' || request()->is('siswa/pelajaran/*/absensi*') || request()->is('siswa/absensi*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Riwayat Absensi
                    </a>
                @endhasrole

                <div class="mt-auto px-3 pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 fw-bold rounded-3 py-2">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <div class="main-content">

            <nav class="navbar navbar-expand-lg top-navbar px-3 px-lg-4 py-3 sticky-top">
                <div class="container-fluid px-0">

                    <button class="navbar-toggler border-0 shadow-none d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-label="Toggle navigation">
                        <i class="fas fa-bars fs-4 text-dark"></i>
                    </button>

                    <span class="navbar-brand mb-0 h5 fw-bold text-dark d-none d-md-block">
                        Panel {{ Auth::user()->roles->pluck('name')->first() }}
                    </span>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="d-none d-sm-block text-end">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                        </div>

                    </div>
                </div>
            </nav>

            <div class="container-fluid px-3 px-lg-4 py-4 pb-5">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
