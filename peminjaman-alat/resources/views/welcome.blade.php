<!DOCTYPE html>
<html lang="eid">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Peminjaman alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://source.unsplash.com/1600x900/?laboratory,workshop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            border-radius: 0 0 20px 20px;
        }

        .feateure-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="bg-light">
    {{-- navbar start --}}

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SIPINJAM</a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">Login</a>
            </div>
        </div>
    </nav>

    {{-- navbar end --}}

    {{-- hero section start --}}

    <div class="hero-section text-center mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Peminjaman Alat Jadi Lebih Mudah</h1>
            <p class="lead mb-4">Sistem manajemen peminjaman alat laboratorium dan bengkel sekolah yang terintegrasi,
                cepat, dan
                transparan.</p>
            <a class="btn btn-lg btn-warning fw-bold px-5">Mulai Peminjaman</a>
        </div>
    </div>

    {{-- hero section end --}}

    {{-- container start --}}

    <div class="container mb-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 py-4">
                    <div class="card-body">
                        <div class="feature-icon">🔍</div>
                        <h4 class="card-title">Cari alat</h4>
                        <p class="card-text">ketersedian stok alat secara real time tanpa perlu bolak balik ke ruang
                            penyimpanan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 py-4">
                    <div class="card-body">
                        <div class="feature-icon">📃</div>
                        <h4 class="card-title">Ajukan Peminjaman</h4>
                        <p class="card-text">Proses pengajuan peminjaman yang praktis melalui sistem dan persetujuan
                            petugas yang cepat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 py-4">
                    <div class="card-body">
                        <div class="feature-icon">🔁</div>
                        <h4 class="card-title">Pengembalian</h4>
                        <p class="card-text">Sistem monitoring pengembalian alat yang terstruktur untuk menghindari
                            kehilangan aset</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- container end --}}

    {{-- footer start --}}

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} Sistem Peminjaman Alat. Dibuat dengan Laravel</small>
        </div>
    </footer>

    {{-- footer end --}}

    {{-- Bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
