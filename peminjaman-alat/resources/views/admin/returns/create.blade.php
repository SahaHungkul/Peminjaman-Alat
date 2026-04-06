@extends('layouts.app')

@section('content')
    {{-- header start --}}
    <div>
        <h3>Proses Pengembalian Alat</h3>
        <a href="{{ route('returns.index') }}">Kembali Ke Riwayat</a>
    </div>
    {{-- header end --}}

    {{-- alert start --}}
    <div>
        Silahkan pilih data peminjaman di bawah ini untuk proses pengembaliannya
    </div>
    {{-- alert end --}}

    {{-- main start --}}
    <div>
        <div>Daftar Alat Sedang dipinjam</div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
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
                                {{ $loan->tanggal_pinjam_rencana }}
                                @if (now() > $loan->tanggal_kembali_rencana)
                                    <span class="badge bg-danger">Lewat Jatuh Tempo</span>
                                @endif
                            </td>
                            <td><span class="badge bg-primary">Sedang dipinjam</span></td>
                            <td>
                                <form action="{{ route('admin.returns.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="loan_id" class="{{ $loan->id }}">

                                    <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('konfirmasi: Barang sudah diterima kembali dan kondisi baik?')">Proses
                                        Kembali</button>
                                </form>
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
