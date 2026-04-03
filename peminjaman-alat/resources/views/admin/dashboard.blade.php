@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h3>dasboard Administrator</h3>
        <p class="text-muted">Selamat Datang, {{ auth()->user()->name }}</p>
    </div>

    {{-- container start --}}

    {{-- card start --}}

    <div class="row mb-4">

        {{-- card User --}}

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-header">Total Pengguna</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $totalUser }}</h2>
                    <p class="card-text">User Terdaftar</p>
                </div>
                <div>
                    <a href="{{ route('user.index') }}" class="text-white text-decoration-none small">Lihat Detail</a>
                    <span class="small">&rarr;</span>
                </div>
            </div>
        </div>

        {{-- card data Alat --}}
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-header">Data Alat</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $totalAlat }} <span class="fs-2">(stok:{{ $totalStok }})</span></h2>
                    <p class="card-text">Jenis Alat Tersedia</p>
                </div>
                <div>
                    <a href="{{ route('tools.index') }}" class="text-white text-decoration-none small">Lihat Detail</a>
                    <span class="small">&rarr;</span>
                </div>
            </div>
        </div>

        {{-- card kategori --}}
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-header"></div>
                <div class="card-body">
                    <h2 class="card-title">{{ $totalKategori }}</h2>
                    <p class="card-text">Kategori Alat </p>
                </div>
                <div>
                    <a href="{{ route('categories.index') }}" class="text-white text-decoration-none small">Lihat Detail</a>
                    <span class="small">&rarr;</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- card Peminjaman AKtif --}}
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-header">Sedang Dipinjam</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $sedangDipinjam }}</h2>
                    <p class="card-text">Pinjaman Aktif </p>
                </div>
                <div>
                    <a href="{{ route('admin.loans.index') }}" class="text-white text-decoration-none small">Pantau</a>
                    <span class="small">&rarr;</span>
                </div>
            </div>
        </div>

        {{-- Card Kembali --}}
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3 h-100">
                <div class="card-header">Sudah Dikembalikan</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $sudahDikembalikan }}</h2>
                    <p class="card-text">Transaksi Selesai</p>
                </div>
                <div>
                    <a href="{{ route('admin.returns.index') }}" class="text-white text-decoration-none small">Pantau</a>
                    <span class="small">&rarr;</span>
                </div>
            </div>
        </div>
    </div>

    {{-- table start --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light fw-bold">
                    Aktivitas Sistem Terakhir
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td class="samll text-muted"{{ $log->created_at->difforHumans }}></td>
                                    <td>
                                        <span class="">{{ ucfirst($log->user->name) }}</span>
                                        <br>
                                        <span class="">{{ ucfirst($log->user->role) }}</span>
                                    </td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ Str::limit($log->deskripsi, 50) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Belum Ada Aktivitas Tercatat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ url('/admin/logs') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua Log</a>
                </div>
            </div>
        </div>
    </div>
    {{-- table end --}}

    {{-- container end --}}
@endsection
