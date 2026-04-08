@extends('layouts.app')

@section('content')
    {{-- header start --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Data Pengguna</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User Baru</a>
    </div>
    {{-- header end --}}

    {{-- form action start --}}
    <div class="mb-3">
        <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2" style="max-width:400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama atau Email...">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>
    {{-- form action end --}}

    {{-- card start --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->role == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge bg-primary">Petugas</span>
                                @else
                                    <span class="badge bg-secondary">Peminjam</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                    Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin mengahpus User ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <td colspan="5" class="text-center">Data User Tidak Ditemukan.</td>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    {{-- card end --}}
@endsection
