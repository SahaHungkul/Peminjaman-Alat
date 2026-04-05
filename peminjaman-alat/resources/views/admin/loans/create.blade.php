@extends('layouts.app')

@section('content')
    <div>
        <div>Tambah Pinjaman Manual</div>
        <div>
            <form action="{{ route('admin.loans.store') }}" method="POST">
                @csrf

                {{-- kolom user --}}
                <div class="mb-3">
                    <label>Pilih peminjaman</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- kolom alat --}}
                <div class="mb-3">
                    <label>Pilih Alat</label>
                    <select name="tool_id" class="form-select" required>
                        <option>-- Pilih Alat --</option>
                        @foreach ($tools as $tool)
                            <option value="{{ $tool->id }}">{{ $tool->nama_alat }} (Stok: {{ $tool->stok }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- kolom tanggal --}}
                <div class="mb-3">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- kolom kembali --}}
                <div class="mb-3">
                    <label>Rencana Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" class="form-control" required>
                </div>

                {{-- kolom status --}}
                <div class="mb-3">
                    <label>Status Awal</label>
                    <select name="status" class="form-control">
                        <option value="pending">Pending (Menunggu Persetujuan)</option>
                        <option value="disetujui">Disetujui (Langsung Bawa)</option>
                        <option value="kembali">Sudah Kembali (Hanya catat riwayat)</option>
                    </select>
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
