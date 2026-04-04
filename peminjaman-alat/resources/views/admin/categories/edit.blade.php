@extends('layouts.app')

@section('content')
    <div>
        <div>
            <div>
                <div>Edit Kategori</div>
                <div>
                    <form action="{{ route('categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="">Nama Kategori</label>
                            <input type="text" name="nama_kategori"
                                class="form-controller @error('nama_kategori') is-invalid
                        @enderror"
                                value{{ old('nama_kategori', $category->nama_kategori ) }} placeholder="Contoh: Elektronik, Furniture, dll" required>
                            @error('nama_kategori')
                            <div class="invalid-feedack">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
