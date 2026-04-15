@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header fw-bold">Tambah Alat Baru</div>
                    <div class="card-body">
                        <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- kolom Nama Alat --}}
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Alat</label>
                                <input type="text" name="nama_alat"
                                    class="form-control @error('nama_alat') is-invalid
                                @enderror"
                                    value="{{ old('nama_alat') }}" required>
                                @error('nama_alat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- kolom kategori --}}
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id"
                                    class="form-select @error('category_id') is-invalid
                                @enderror"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">

                                {{-- kolom stok --}}
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Stok</label>
                                    <input type="number" name="stok"
                                        class="form-control @error('stok') is-invalid
                                @enderror"
                                        value="{{ old('stok', 1) }}" min="0" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- kolom gambar --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gambar Alat</label>
                                    <input type="file" name="gambar"
                                        class="form-control @error('gambar') is-invalid
                                    @enderror"
                                        accept="image/*" id="inputGambar">
                                    <small class="text-muted">Format: JPG, PNG, Maks: 2MB</small>
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div id="containerGambar" class="mt-3 d-none">
                                        <img id="previewGambar" src="#" alt="none" class="img-thumbnail"
                                            style="max-height: 170px;">
                                    </div>
                                </div>
                            </div>

                            {{-- deskripsi --}}
                            <div class="mb-3">
                                <label class="form-label">Deskripsi / spesifikasi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                            </div>

                            {{-- button --}}
                            <div>
                                <a href="{{ route('tools.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const inputGambar = document.getElementById('inputGambar');
        const previewContainer = document.getElementById('containerGambar');
        const gambarPreview = document.getElementById('previewGambar');

        inputGambar.onchange = evt => {
            const [file] = inputGambar.files;

            if (file) {
                // 1. Buat URL gambar dari file yang dipilih
                gambarPreview.src = URL.createObjectURL(file);

                // 2. Munculkan kontainer preview yang tadi disembunyikan
                previewContainer.classList.remove('d-none');
            } else {
                // Jika batal memilih file, sembunyikan kembali
                previewContainer.classList.add('d-none');
            }
        }
    </script>
@endsection
