@extends('layouts.app')

@section('content')
    <div class="card col-md-8 mx-auto">
        <div class="card-header fw-bold">Edit Peminjaman #{{ $loan->id }}</div>
        <div class="card-body">
            <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- kolom user --}}
                <div class="mb-3">
                    <label>Peminjam</label>
                    <select name="user_id" class="form-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- kolom alat --}}
                <div class="mb-3">
                    <label>Alat</label>
                    <select name="tool_id" class="form-select">
                        <option>-- Pilih Alat --</option>
                        @foreach ($tools as $tool)
                            <option value="{{ $tool->id }}" {{ $loan->tool_id == $tool->id ? 'selected' : '' }}>
                                {{ $tool->nama_alat }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold mb-1">Jumlah</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-box-seam"></i></span>
                        <input type="number" name="qty" class="form-control border-start-0" value="1"
                            min="1" max="{{ $tool->stok }}" required>
                    </div>
                </div>

                {{-- kolom tanggal --}}
                <div class="row mb-3">
                    <div class="col">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ $loan->tanggal_pinjam }}"
                            required>
                    </div>
                    <div class="col">
                        <label>Rencana Kembali</label>
                        <input type="date" name="tanggal_kembali_rencana" class="form-control"
                            value="{{ $loan->tanggal_kembali_rencana }}">
                    </div>
                </div>

                {{-- kolom status --}}
                <div class="mb-3">
                    <label>Status Awal</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $loan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ $loan->status == 'disetujui' ? 'selected' : '' }}>Disetujui </option>
                        <option value="kembali" {{ $loan->status == 'kembali' ? 'selected' : '' }}>Sudah Kembali </option>
                        <option value="ditolak" {{ $loan->status == 'ditolak' ? 'selected' : '' }}>Ditolak </option>
                    </select>
                    <small class="text-danger">Mengubah status 'Disetujui' ke 'Kembali' akan menambahkan stok otomatis
                    </small>
                </div>

                <button class="btn btn-success">Update Data</button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
