@extends('layouts.app')

@section('content')
    {{-- header start --}}
    <div>
        <h3>Kelola Data Alat</h3>
        <a href="{{ route('tools.create') }}">+ Tambah Alat Baru</a>
    </div>
    {{-- header end --}}

    {{-- container start --}}
    <div>
        <div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Gambar</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tools as $key => $tool)
                            <tr>
                                <td>{{ $tools->firstItem() + $key }}</td>
                                <td>
                                    @if ($tool->gambar)
                                        <img src="{{ asset('storage/' . $tool->gambar) }}" alt="img" class="img-thumbnail"
                                            style="heigh:60px">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $tool->nama_alat }}</strong>
                                    <div>{{ $tool->deskripsi }}</div>
                                </td>
                                <td>
                                    <span>{{ $tool->category_nama_kategori }}</span>
                                </td>
                                <td>{{ $tool->stok }}</td>
                                <td>
                                    <a href="{{ route('tools.edit',$tool->id) }}" class="btn btnn-warning btn-sm">Edit</a>
                                    <form action="{{ route('tools.destroy') . $tool->id }}" method="POST" class="d-inline" onsubmit="return confirm('yakin ingin mengahpus alat ini? Data peminjaman terkait mungkin akan erro.r');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
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
                {{ $tool->link('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- container end --}}
@endsection
