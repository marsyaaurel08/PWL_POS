@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <h3>Edit Profil</h3>

    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tampilkan Foto Sekarang --}}
        <div class="mb-3 text-center">
            <img src="{{ $profil && $profil->foto ? asset('storage/foto/' . $profil->foto) : asset('storage/foto/default.png') }}"
                class="rounded-circle"
                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ccc;">
        </div>

        {{-- Input Upload Foto --}}
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Profil</label>
            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            @error('foto')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ url('/profil') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
