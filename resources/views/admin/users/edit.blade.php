@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold">Edit User: {{ $user->name }}</div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="">Nama Lengkap</label>
                            <input type="text" name="name" id=""
                                class="form-control @error('email') is-invalid
                            @enderror"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Email Address</label>
                            <input type="email" name="email" id=""
                                class="form-control @error('email') is-invalid
                            @enderror"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Role (Hak Akses)</label>
                            <select name="role" class="form-select">
                                <option value="">-- Pilih Role --</option>
                                <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas Lab
                                </option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin
                                    memperbarui
                                    password)</small></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid
                            @enderror"
                                minlength="6">
                            @error('password')
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">batal</a>
                            <button class="btn btn-success" type="submit">
                                Edit User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
