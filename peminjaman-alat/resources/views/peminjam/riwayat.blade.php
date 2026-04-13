@extends('layouts.app')

@section('content')
    <h3>Riwayat Peminjaman Saya</h3>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Waktu peminjaman</th>
                        <th>Status & Denda</th>
                        <th class="text-center">aksi</th>
                        {{-- <th>Catatan</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->tool->nama_alat }}</td>
                            <td>{{ $loan->tanggal_pinjam }} - {{ $loan->tanggal_kembali_rencana }}</td>
                            <td>
                                @if ($loan->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                @elseif($loan->status == 'disetujui')
                                    <span class="badge bg-primary">Sedang Dipinjam</span>
                                @elseif($loan->status == 'kembali')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif($loan->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($loan->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                                @if ($loan->denda > 0)
                                    <div class="mt-1">
                                        <small class="text-danger fw-bold">Denda: Rp
                                            {{ number_format($loan->denda, 0, ',', '.') }}</small>
                                        <br>
                                        @if ($loan->status_denda == 'lunas')
                                            <span class="badge badge-sm bg-success" style="font-size: 0.7rem;">Lunas</span>
                                        @else
                                            <span class="badge badge-sm bg-danger" style="font-size: 0.7rem;">Belum
                                                Lunas</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($loan->status == 'disetujui')
                                    <form action="{{ route('peminjam.return', $loan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-arrow-return-left"></i> kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            {{-- <td>
                                @if ($loan->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-clock-history"></i> Menunggu Dicek Petugas
                                    </span>
                                @elseif($loan->status == 'kembali')
                                    <span class="badge bg-success">Selesai / Dikembalikan</span>
                                @else
                                    <span>-</span>
                                @endif
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $loans->links('pagination::bootstrap-5') }}</div>
    </div>
@endsection
