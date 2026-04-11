@extends('layouts.app')

@section('content')
    <h3>Riwayat Peminjaman Saya</h3>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <th>aksi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->tool->nama_alat }}</td>
                            <td>{{ $loan->tanggal_pinjam }}</td>
                            <td>{{ $loan->tanggal_kembali_rencana }}</td>
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
                            </td>
                            <td>
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
                            <td>
                                @if($loan->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-clock-history"></i> Menunggu Dicek Petugas
                                    </span>
                                @elseif($loan->status == 'kembali')
                                    <span class="badge bg-success">Selesai / Dikembalikan</span>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
