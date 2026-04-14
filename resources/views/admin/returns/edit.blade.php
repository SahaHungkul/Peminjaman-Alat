@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Edit Data Pengembalian</div>
                <div class="card-body">
                    <form action="{{ route('admin.returns.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Peminjam</label>
                            <input type="text" class="form-control" value="{{ $loan->user->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label>Alat</label>
                            <input type="text" class="form-control" value="{{ $loan->tool->nama_alat }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Kembali Aktual</label>
                            <input type="date" name="tanggal_kembali_aktual" class="form-control"
                                value="{{ $loan->tanggal_kembali_aktual }}" required>
                            <small class="text-muted">Ubah tanggal ini jika admin salah input waktu
                                pengembalian.</small>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <label>Denda (RP)</label>
                                <input type="number" name="denda" class="form-control" value="{{ $loan->denda }}"
                                    placeholder="0">
                                <small class="text-muted">Isi 0 jika tidak ada denda.</small>
                            </div>
                            <label>Status Denda</label>
                            <select name="status_denda" class="form-select @error('status_denda') is-invalid @enderror"
                                required>
                                <option value="tidak_ada" {{ $loan->status_denda == 'tidak_ada' ? 'selected' : '' }}>Tidak
                                    Ada Denda</option>
                                <option value="belum_bayar" {{ $loan->status_denda == 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Lunas</option>
                                <option value="lunas" {{ $loan->status_denda == 'lunas' ? 'selected' : '' }}>Lunas / Sudah
                                    Dibayar</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
