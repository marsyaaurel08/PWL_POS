@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h3 class="text-center mb-4">Edit Profil</h3>

                    {{-- Tampilkan Foto Sekarang --}}
                    <div class="text-center mb-4">
                        <img src="{{ $profil && $profil->foto ? asset('storage/foto/' . $profil->foto) : asset('storage/foto/default.png') }}"
                            class="rounded-circle shadow"
                            style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #dee2e6;">
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="foto" class="form-label">Upload Foto Baru</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/profil') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
