@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            {{-- Branding --}}
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">
                    <i class="bi bi-tools text-primary"></i> PINJMAIN AJA
                </h2>
                <p class="text-muted">Buat akun untuk mulai meminjam alat</p>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 text-center">Daftar Akun Baru</h4>

                    <form action="{{ url('register') }}" method="POST">
                        @csrf

                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 ps-0"
                                    placeholder="Nama lengkap Anda" required autofocus>
                            </div>
                        </div>

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 ps-0"
                                    placeholder="akun@app.com" required>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Input Password --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control bg-light border-start-0 ps-0"
                                        placeholder="••••••••" required>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Konfirmasi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-shield-check text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation"
                                        class="form-control bg-light border-start-0 ps-0" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Daftar --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            Buat Akun Sekarang <i class="bi bi-person-plus-fill ms-1"></i>
                        </button>
                    </form>

                    <hr class="my-4 text-muted opacity-25">

                    <p class="mb-0 text-center text-muted small">
                        Sudah punya akun? <a href="{{ route('login') }}"
                            class="text-primary fw-bold text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-4 mb-5">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-chevron-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
