<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aplikasi Peminjaman Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    {{-- navbar start --}}
    <nav  class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            {{-- navbar logo --}}
            <a href="#" class="navbar-brand">Sistem Peminjaman</a>

            {{-- navbar nav --}}
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    @auth
                        {{-- navbar untuk admin --}}
                        @if (auth()->user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Kelola Kategori</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('tools.index') }}">Kelola Alat</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Kelola User</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('loans.index') }}">Kelola Peminjaman</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('returns.index') }}">Kelola Pengembalian</a></li>

                            {{-- navbar untuk petugas --}}
                        @elseif(auth()->user()->role == 'petugas')
                            <li class="nav-item"><a class="nav-link" href="/petugas/laporan">Laporan</a></li>

                            {{-- navbar untuk peminjam --}}
                        @elseif(auth()->user()->role == 'peminjam')
                            <li class="nav-item"><a class="nav-link" href="/petugas/laporan">Daftar</a></li>
                            <li class="nav-item"><a class="nav-link" href="/petugas/laporan">Riwayat Saya</a></li>
                        @endif
                    @endauth
                </ul>

                {{-- dropdown --}}
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- navbar end --}}

    {{-- container start --}}

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    {{-- container end --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
