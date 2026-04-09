@extends('layouts.app')

@section('content')
    {{-- header start --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Proses Pengembalian Alat</h3>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">Kembali Ke Riwayat</a>
    </div>
    {{-- header end --}}

    {{-- alert start --}}
    <div class="alert alert-info">
        Silahkan pilih data peminjaman di bawah ini untuk proses pengembaliannya
    </div>
    {{-- alert end --}}

    {{-- main start --}}
    <div class="card">
        <div class="card-header bg-primary text-white">Daftar Alat Sedang Dipinjam</div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                        <td>Denda</td>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeLoans as $loan)
                        <tr>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->tool->nama_alat }}</td>
                            <td>{{ $loan->tanggal_pinjam }}</td>
                            <td>
                                {{ $loan->tanggal_kembali_rencana }}
                                @if (now() > $loan->tanggal_kembali_rencana)
                                    <span class="badge bg-danger">Lewat Jatuh Tempo</span>
                                @endif
                            </td>
                            <td><span class="badge bg-primary">Sedang dipinjam</span></td>
                            <td>
                                <form action="{{ route('admin.returns.store') }}" method="POST" id="form-return-{{ $loan->id }}">
                                    @csrf
                                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">

                                    {{-- Tambahkan input denda --}}
                                    <input type="number" name="denda" class="form-control form-control-sm" min="0"
                                        value="0" style="width: 130px" placeholder="0">
                                    <small class="text-muted">Isi 0 jika tidak ada denda</small>
                                </form>
                            </td>
                            <td>
                                <button type="submit" form="form-return-{{ $loan->id }}" class="btn btn-success btn-sm"
                                    onclick="return confirm('Konfirmasi: Barang sudah diterima kembali dan kondisi baik?')">
                                    Proses Kembali
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada alat yang sedang dipinjam saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- main end --}}
@endsection
