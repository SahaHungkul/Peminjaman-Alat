@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-5 col-lg-4">
            {{-- Logo atau Nama Brand di atas Card --}}
            <div class="text-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-tools text-primary"></i> PINJAMIN AJA
                </h2>
                <p class="text-muted">Silahkan masuk ke akun Anda</p>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 text-center">Login Aplikasi</h4>

                    <form action="{{ url('login') }}" method="POST">
                        @csrf

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0"
                                       placeholder="akun@app.com" required autofocus>
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-semibold">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0"
                                       placeholder="••••••••" required>
                            </div>
                        </div>

                        {{-- Tombol Login --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            Masuk Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                        </button>
                    </form>

                    <hr class="my-4 text-muted opacity-25">

                    <p class="mb-0 text-center text-muted small">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Sekarang</a>
                    </p>
                </div>
            </div>

            {{-- Link Kembali ke Home --}}
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-chevron-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
