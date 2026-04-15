@extends('layouts.app')

@section('content')
    <div class="card col-md-8 mx-auto">
        <div class="card-header fw-bold">Tambah Peminjaman Manual</div>
        <div class="card-body">
            <form action="{{ route('admin.loans.store') }}" method="POST">
                @csrf

                {{-- kolom user --}}
                <div class="mb-3">
                    <label>Pilih Peminjam</label>
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
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    {{-- kolom kembali --}}
                    <div class="col">
                        <label>Rencana Kembali</label>
                        <input type="date" name="tanggal_kembali_rencana" class="form-control" required>
                    </div>
                </div>

                {{-- kolom status --}}
                <div class="mb-3">
                    <label>Status Awal</label>
                    <select name="status" class="form-select">
                        <option value="pending">Pending (Menunggu Persetujuan)</option>
                        <option value="disetujui">Disetujui (Langsung Bawa)</option>
                        <option value="kembali">Sudah Kembali (Hanya catat riwayat)</option>
                    </select>
                </div>

                <button class="btn btn-primary">Simpan Data</button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
