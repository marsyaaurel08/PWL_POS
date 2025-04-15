@extends('layouts.template')

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="card text-center p-4 shadow-sm border rounded-4" style="width: 320px; background-color: #f8f9fa;">
        {{-- Foto Profil --}}
        <div class="mb-3">
            <img
                src="{{ $profil && $profil->foto ? asset('storage/foto/' . $profil->foto) : asset('storage/foto/default.png') }}"
                alt="Foto Profil"
                class="rounded-circle shadow-sm"
                style="width: 140px; height: 140px; object-fit: cover; border: 3px solid #dee2e6;">
        </div>

        {{-- Info User --}}
        <h4 class="mb-1">{{ Auth::user()->nama }}</h4>
        <b>
            <p class="text-muted">{{ Auth::user()->username }}</p>
        </b>

        {{-- Tombol Edit dan Hapus --}}
        <div class="d-grid gap-2">
            <a href="{{ route('profil.edit') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit Foto Profil
            </a>

            {{-- Tampilkan tombol hapus hanya jika foto bukan default --}}
            @if($profil && $profil->foto && $profil->foto !== 'default.png')
                <a href="{{ route('profil.deleteFoto') }}"
                   onclick="return confirm('Yakin ingin menghapus foto profil?')"
                   class="btn btn-danger btn-sm">
                   <i class="fas fa-trash me-1"></i> Hapus Foto Profil
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
