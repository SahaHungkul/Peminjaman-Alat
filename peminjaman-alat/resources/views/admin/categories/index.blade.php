@extends('layouts.app')

@section('content')
    <div>
        <div>
            <div>
                <h3>Kelola Kategori Alat</h3>
                <a href="{{ route('categories.create') }}">+ Tambah Kategori</a>
            </div>

            <div>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Jumlah Alat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $cat)
                            <tr>
                                <td>{{ $categories->firstItem() + $key }}</td>
                                <td>{{ $cat->nama_kategory }}</td>
                                <td>
                                    <span>
                                        {{ $cat->tools_count }} Item
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('categories.edit') }}">Edit</a>
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('hapus Kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button>Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    Belum ada Kategori
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $categories->link('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
