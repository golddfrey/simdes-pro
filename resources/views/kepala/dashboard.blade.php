@extends('layouts.windmill')

@section('title','Dashboard Kepala Keluarga')

@section('content')
  <div class="max-w-5xl mx-auto px-6 py-12">
    <!-- Welcome text above the centered card -->
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold">Selamat datang, {{ $kepala->nama }}</h1>
      <p class="mt-2 text-gray-600">Kelola data keluarga Anda, cetak Kartu Keluarga, atau kirim kritik & saran melalui menu di samping.</p>
      @if(session('status'))
        <div class="mt-4 inline-block p-3 rounded bg-green-50 text-green-800">{{ session('status') }}</div>
      @endif
    </div>

    <!-- Centered large profile card -->
    <div class="flex justify-center">
      <div class="w-full md:w-full lg:w-11/12 space-y-6">
        @php
          $actions = '<a href="' . route('kepala.anggota.index') . '" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded mr-2">Lihat Anggota Keluarga</a>' .
                     '<a href="' . route('kepala.anggota.create') . '" class="inline-block px-4 py-2 bg-white border border-gray-200 rounded">Tambah Anggota</a>';
        @endphp
        @include('kepala.components.profile-card', ['person' => $kepala, 'actions' => $actions])
      </div>
    </div>
  </div>
@endsection
