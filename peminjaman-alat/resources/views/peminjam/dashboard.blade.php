@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Eksplorasi Alat</h3>
            <p class="text-muted small">Pilih perlengkapan yang kamu butuhkan untuk praktik hari ini.</p>
        </div>

        {{-- Form Pencarian --}}
        <form action="{{ route('peminjam.dashboard') }}" method="GET" class="d-flex gap-2">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" value="{{ request('search') }}"
                    placeholder="Cari alat...">
                <button type="submit" class="btn btn-primary px-4">Cari</button>
            </div>
        </form>
    </div>

    {{-- container --}}
    <div class="row mt-4">
        @forelse ($tools as $tool)
            <div class="col-md-4 col-lg-3 mb-4">
                {{-- card start --}}
                <div class="card h-100 border-0 shadow-sm hover-shadow-md transition-all">
                    {{-- Bagian Gambar --}}
                    <div class="position-relative">
                        @if ($tool->gambar)
                            <img src="{{ asset('storage/' . $tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                                class="card-img-top object-fit-cover" style="height: 180px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bi bi-image text-muted display-4"></i>
                            </div>
                        @endif

                        {{-- Badge Kategori --}}
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-dark opacity-75 fw-normal">{{ $tool->category->nama_kategori }}</span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-1">{{ $tool->nama_alat }}</h5>
                        <p class="text-muted small mb-3 grow">{{ Str::limit($tool->deskripsi, 80) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small text-muted">Sisa Stok:</span>
                            <span
                                class="badge {{ $tool->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3">
                                {{ $tool->stok }} Unit
                            </span>
                        </div>

                        @if ($tool->stok > 0)
                            <form action="{{ url('/peminjam/ajukan') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tool_id" value="{{ $tool->id }}">

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold mb-1">Rencana Pengembalian</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="bi bi-calendar-event"></i></span>
                                        <input type="date" name="tanggal_kembali"
                                            class="form-control bg-light border-start-0" required
                                            min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                    <i class="bi bi-cart-plus me-1"></i> Pinjam Alat
                                </button>
                            </form>
                        @else
                            <button class="btn btn-light w-100 text-muted" disabled>
                                <i class="bi bi-dash-circle me-1"></i> Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            {{-- card end --}}
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-archive display-1 text-muted opacity-25"></i>
                <p class="mt-3 text-muted">Alat yang kamu cari tidak ditemukan.</p>
                <a href="{{ route('peminjam.dashboard') }}" class="btn btn-link">Lihat semua alat</a>
            </div>
        @endforelse
    </div>
@endsection
