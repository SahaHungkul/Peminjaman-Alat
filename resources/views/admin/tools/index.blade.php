@extends('layouts.app')

@section('content')
    {{-- header start --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Data Alat</h3>
        <a href="{{ route('tools.create') }}" class="btn btn-primary">+ Tambah Alat Baru</a>
    </div>
    {{-- header end --}}

    {{-- container start --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Gambar</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tools as $key => $tool)
                            <tr>
                                <td>{{ $tools->firstItem() + $key }}</td>
                                <td>
                                    @if ($tool->gambar)
                                        <img src="{{ asset('storage/' . $tool->gambar) }}" alt="img"
                                            class="img-thumbnail" style="height: 60px">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $tool->nama_alat }}</strong>
                                    <div>{{ $tool->deskripsi }}</div>
                                </td>
                                <td>
                                    <span>{{ $tool->category->nama_kategori }}</span>
                                </td>
                                <td>{{ $tool->stok }}
                                    @if ($tool->stok <= 2 && $tool->stok > 0)
                                        <span class="badge bg-warning text-dark animate-pulse">
                                            <i class="bi bi-exclamation-triangle"></i> Stok Menipis
                                        </span>
                                    @elseif($tool->stok == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tools.edit', $tool->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus alat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4"> Belum Ada alat. Silahkan tambah data Baru</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tools->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- container end --}}
@endsection
