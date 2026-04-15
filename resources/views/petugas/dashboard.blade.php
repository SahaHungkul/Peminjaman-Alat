@extends('layouts.app')

@section('content')
    <h3>Permintaan Peminjaman Masuk</h3>
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">Menunggu Persetujuan</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->tool->nama_alat }} x{{ $loan->qty }}</td>
                            <td>{{ $loan->tanggal_pinjam }}</td>
                            <td>{{ $loan->tanggal_kembali_rencana }}</td>
                            <td>
                                <form action="{{ url('/petugas/approve/' . $loan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ url('/petugas/reject/' . $loan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada permintaan baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $loans->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>

    <h3>Daftar Sedang Dipinjam (Belum Kembali)</h3>
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Monitor Peminjaman</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeLoans as $active)
                        <tr>
                            <td>{{ $active->user->name }}</td>
                            <td>{{ $active->tool->nama_alat }} x{{ $active->qty }}</td>
                            <td>{{ $active->tanggal_pinjam }} <br>
                                <small class="text-muted">hingga: {{ $active->tanggal_kembali_rencana }}</small>
                            </td>
                            <td>
                                {{-- <span class="badge bg-primary">{{ $active->status }}</span> --}}
                                @if ($active->status == 'disetujui')
                                    <span class="badge bg-primary">disetujui</span>
                                @else
                                    <span class="badge bg-warning text-dark">Selesai</span>
                                @endif
                            </td>

                            <form action="{{ url('/petugas/return/' . $active->id) }}" method="POST"
                                id="form-return-{{ $active->id }}" enctype="multipart/form-data">
                                @csrf
                                {{-- Kolom denda --}}

                                <td>
                                    <input type="number" name="denda" class="form-control form-control-sm" min="0"
                                        value="0" style="width: 130px" placeholder="0">
                                    <small class="text-muted">Isi 0 jika tidak ada denda</small>
                                </td>

                                <td>
                                    <input type="file" name="gambar" class="form-control form-control-sm"
                                        accept="image/*" required style="width: 200px">
                                </td>

                                <td>
                                    <button type="submit" form="form-return-{{ $active->id }}"
                                        class="btn btn-primary btn-sm"
                                        onclick="return confirm('Konfirmasi pengembalian alat?')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $activeLoans->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>

    <h3>Daftar Sudah Dikembalikan</h3>
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Monitor Peminjaman</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>denda & Status</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sudahDikembalikan as $sudah)
                        <tr>
                            <td>{{ $sudah->user->name }}</td>
                            <td>{{ $sudah->tool->nama_alat }} x{{ $sudah->qty }}</td>
                            <td>{{ $sudah->tanggal_pinjam }} {{ $sudah->tanggal_kembali_rencana }} <br>
                                <small class="text-muted">Kembali: {{ $sudah->tanggal_kembali_aktual }}</small>
                            </td>
                            <td>
                                @if ($sudah->denda > 0)
                                    <div class="fw-bold text-danger">Rp {{ number_format($sudah->denda, 0, ',', '.') }}
                                    </div>
                                    @if ($sudah->status_denda == 'belum_bayar')
                                        <span class="badge bg-danger">Belum Lunas</span>
                                        <form action="{{ route('petugas.bayar', $sudah->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-success fw-bold"
                                                onclick="return confirm('Konfirmasi pelunasan denda?')">
                                                [Tandai Lunas]
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                @else
                                    <span class="text-muted small"> - </span>
                                @endif
                                {{-- <span class="badge bg-primary">{{ $sudah->status }}</span> --}}
                            </td>
                            <td>
                                @if ($sudah->gambar)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalBukti{{ $sudah->id }}">
                                        <i class="bi bi-camera"></i>
                                    </button>

                                    <div class="modal fade" id="modalBukti{{ $sudah->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Bukti Pengembalian:
                                                        {{ $sudah->tool->nama_alat }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center ">
                                                    <img src="{{ asset('storage/' . $sudah->gambar) }}"
                                                        class="img-fluid rounded shadow-sm mb-2" alt="Foto Bukti">
                                                    {{-- <div class="text-start mt-3 p-3  border rounded">
                                                        <p class="mb-1"><strong>Denda:</strong> Rp
                                                            {{ number_format($sudah->denda, 0, ',', '.') }}</p>
                                                        <p class="mb-0 text-muted"><strong>Catatan:</strong>
                                                            {{ $sudah->catatan_petugas ?? 'Tidak ada catatan.' }}</p>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Tidak ada bukti</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $sudahDikembalikan->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
