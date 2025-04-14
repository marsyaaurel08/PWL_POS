@extends('layouts.template')

@section('content')
<div class="d-flex justify-content-center mt-5">
    <div class="card text-center p-4 shadow" style="width: 300px;">
        {{-- Foto Profil --}}
        <div class="mx-auto mb-3">
            <img 
                src="{{ $profil && $profil->foto ? asset('storage/foto/' . $profil->foto) : asset('default.png') }}" 
                alt="Foto Profil" 
                class="rounded-circle" 
                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ccc;">
        </div>

        {{-- Info User --}}
        <h4 class="mb-1">{{ Auth::user()->nama }}</h4>
        <b><p class="text-muted">{{ Auth::user()->username }}</p></b>

        {{-- Tombol Edit --}}
        <a href="{{ route('profil.edit') }}" class="btn btn-primary btn-sm">Edit Foto Profil</a>
    </div>
</div>
@endsection
