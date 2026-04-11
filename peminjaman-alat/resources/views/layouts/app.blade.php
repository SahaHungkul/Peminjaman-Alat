<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PINJAMIN AJA - Sistem Peminjaman Alat</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .navbar {
            background-color: #212529 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .navbar-brand .bi-tools {
            color: #0d6efd;
        }

        .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.show {
            color: #000 !important;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }

        /* Perbaikan Z-Index Dropdown agar tidak tertutup konten */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.8rem;
            z-index: 1050;
        }

        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        footer {
            background-color: #212529;
            color: rgba(255, 255, 255, 0.6);
            padding: 1.5rem 0;
            margin-top: auto;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 sticky-top">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                <i class="bi bi-tools me-2 text-primary"></i> PINJAMIN AJA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        {{-- Menu Links tetap sama --}}
                        @if (auth()->user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Kategori</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('tools.index') }}">Alat</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">User</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.loans.index') }}">Peminjaman</a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('admin.returns.index') }}">Pengembalian</a></li>
                        @elseif(auth()->user()->role == 'petugas')
                            <li class="nav-item"><a class="nav-link" href="/petugas/laporan">Laporan</a></li>
                        @elseif(auth()->user()->role == 'peminjam')
                            <li class="nav-item"><a class="nav-link" href="/peminjam/dashboard">Pinjam Alat</a></li>
                            <li class="nav-item"><a class="nav-link" href="/peminjam/riwayat">Riwayat</a></li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn btn-outline-light border-0 py-1 px-3" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                <li>
                                    <div class="dropdown-header small text-uppercase fw-bold text-muted">Akses:
                                        {{ auth()->user()->role }}</div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>

                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-4">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        {{-- Flash Messages (Sama seperti sebelumnya) --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ... (Flash error dan lainnya) ... --}}

        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} <strong>PINJAMIN AJA</strong>. Semua Hak Dilindungi.</small>
        </div>
    </footer>

    {{-- Script dipindah ke paling bawah sebelum penutup body --}}
</body>

</html>
