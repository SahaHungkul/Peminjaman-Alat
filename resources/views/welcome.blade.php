<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PINJAMIN AJA - Sistem Peminjaman Alat</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-section {
            /* Menggunakan gambar placeholder yang lebih stabil atau warna solid gradient jika link mati */
            background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(10, 88, 202, 0.9)),
                        url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            display: inline-block;
            margin-bottom: 1.2rem;
            padding: 15px;
            background: #f0f7ff;
            border-radius: 15px;
            line-height: 1;
        }

        .card-feature {
            transition: all 0.3s ease;
            border-radius: 20px !important;
        }

        .card-feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #000;
            transition: all 0.3s;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- navbar start --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <i class="bi bi-box-seam-fill me-2 text-primary"></i> PINJAMIN AJA
            </a>
            <div class="ms-auto">
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-light px-4 me-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4 shadow-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    {{-- navbar end --}}

    {{-- hero section start --}}
    <div class="hero-section text-center mb-5">
        <div class="container text-white">
            <h1 class="display-3 fw-bold mb-3">Peminjaman Alat Jadi Lebih Mudah</h1>
            <p class="lead mb-5 opacity-90 mx-auto" style="max-width: 700px;">
                Sistem manajemen peminjaman alat laboratorium dan bengkel sekolah yang terintegrasi,
                cepat, dan transparan.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="{{ route('login') }}" class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow">
                    Mulai Peminjaman Sekarang
                </a>
            </div>
        </div>
    </div>
    {{-- hero section end --}}

    {{-- container start --}}
    <div class="container mb-auto">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="card card-feature h-100 shadow-sm border-0 py-5 px-3">
                    <div class="card-body">
                        <div class="feature-icon">🔍</div>
                        <h4 class="fw-bold">Cari Alat</h4>
                        <p class="text-muted">Cek ketersediaan stok alat secara real-time tanpa perlu bolak-balik ke ruang penyimpanan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-feature h-100 shadow-sm border-0 py-5 px-3">
                    <div class="card-body">
                        <div class="feature-icon">📝</div>
                        <h4 class="fw-bold">Ajukan Peminjaman</h4>
                        <p class="text-muted">Proses pengajuan peminjaman praktis melalui sistem dengan konfirmasi petugas yang cepat.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-feature h-100 shadow-sm border-0 py-5 px-3">
                    <div class="card-body">
                        <div class="feature-icon">🔄</div>
                        <h4 class="fw-bold">Pengembalian</h4>
                        <p class="text-muted">Monitoring pengembalian alat yang terstruktur untuk menjamin keamanan aset sekolah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- container end --}}

    {{-- footer start --}}
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 small opacity-75">
                &copy; {{ date('Y') }} <strong>PINJAMIN AJA</strong>. Dibuat dengan dedikasi menggunakan Laravel.
            </p>
        </div>
    </footer>
    {{-- footer end --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
