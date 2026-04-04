@extends('layouts.app')

@section('content')
    <div>
        <div>
            <div class="card">
                <div class="card-header fw-bold">Tambah User Baru</div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="">Nama Lengkap</label>
                            <input type="text" name="name" id=""
                                class="form-control @error('email') is-invalid
                            @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="">Email Address</label>
                            <input type="email" name="email" id=""
                                class="form-control @error('email') is-invalid
                            @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="">Role (Hak Akses)</label>
                            <select name="role" class="form-select">
                                <option value="">-- Pilih Role --</option>
                                <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam
                                    (Siswa/Guru)</option>
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Lab
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="">Password </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid
                            @enderror"
                                required minlength="6">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">batal</a>
                            <button class="btn btn-primary" type="submit">
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
