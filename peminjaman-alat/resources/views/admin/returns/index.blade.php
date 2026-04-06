@extends('layouts.app')

@section('content')
    <div>
        <h3>Pengembalian Alat</h3>
        <a href="{{ route('returns.create') }}">+ Tambah Pengembalian Manual</a>
    </div>

    <div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali (Aktual)</th>
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
                            <td>{{ $r->petugas ? $r->petugas->name : 'Admin' }}</td>
                            <td>
                                <a href="{{ route('returns.edit', $r->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('returns.destroy', $r->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus Riwayat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $returns->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
