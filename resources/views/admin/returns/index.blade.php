@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Pengembalian Alat</h3>
        <a href="{{ route('admin.returns.create') }}" class="btn btn-success">+ Proses Pengembalian Baru</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>NO</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali (Aktual)</th>
                        <th>Denda</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $key => $r)
                        <tr>
                            <td>{{ $returns->firstItem() + $key }}</td>
                            <td>{{ $r->user->name }}</td>
                            <td>{{ $r->tool->nama_alat }}</td>
                            <td>{{ $r->tanggal_pinjam }}</td>
                            <td>
                                {{ $r->tanggal_kembali_aktual }}
                                @if ($r->tanggal_kembali_aktual > $r->tanggal_kembali_rencana)
                                    <span class="badge bg-danger">Telat</span>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>

                            {{-- Kolom Denda --}}
                            <td>
                                @if ($r->denda > 0)
                                    Rp {{ number_format($r->denda, 0, ',', '.') }} <br>
                                    @if ($r->status_denda == 'tidak_ada')
                                        <span class="badge bg-secondary">Tidak Ada</span>
                                    @elseif ($r->status_denda == 'belum_bayar')
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @elseif ($r->status_denda == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                @else
                                    <span class="text-muted"> - </span>
                                @endif
                            </td>

                            <td>{{ $r->petugas ? $r->petugas->name : 'Admin' }}</td>

                            <td>
                                <a href="{{ route('admin.returns.edit', $r->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.returns.destroy', $r->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus Riwayat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                    data-bs-target="#viewPhoto{{ $r->id }}">
                                    <i class="bi bi-camera"></i>
                                </button>
                            </td>
                        </tr>
                        <div class="modal fade" id="viewPhoto{{ $r->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Bukti Pengembalian - {{ $r->tool->nama_alat }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        @if ($r->gambar)
                                            <img src="{{ asset('storage/' . $r->gambar) }}"
                                                class="img-fluid rounded shadow" alt="Foto Bukti">
                                            @if ($r->catatan_petugas)
                                                <div class="mt-3 p-2 bg-light border rounded">
                                                    <strong>Catatan Petugas:</strong><br>
                                                    <span class="text-muted small">{{ $r->catatan_petugas }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="py-5 text-muted">
                                                <i class="bi bi-image" style="font-size: 3rem;"></i><br>
                                                Tidak ada foto bukti yang diunggah.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data pengembalian.</td> {{-- ← sesuaikan colspan --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $returns->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
