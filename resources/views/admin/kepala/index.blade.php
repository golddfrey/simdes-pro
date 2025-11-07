@extends('layouts.windmill')

@section('title','Data Kepala Keluarga')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-semibold mb-4">Data Kepala Keluarga</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ openId: null, loadedIds: {} }">
      @foreach($kepalas as $k)
        <div class="bg-white rounded shadow p-6">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-800">{{ $k->nama }}</h3>
              <p class="text-sm text-gray-500">NIK: {{ $k->nik }}</p>
              <p class="text-sm text-gray-500 mt-1">Anggota: <span class="font-medium text-gray-700">{{ $k->anggota_count }}</span></p>
            </div>
            <div class="text-right">
              <button @click="(function(){ const id = {{ $k->id }}; openId = openId === id ? null : id; if (openId === id && !loadedIds[id]) { fetch('{{ route('admin.kepala.anggota', $k->id) }}').then(r => r.text()).then(html => { $refs['anggota-'+id].innerHTML = html; loadedIds[id] = true; }); } })()" class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-md shadow-sm hover:bg-gray-50 text-sm" :aria-expanded="openId === {{ $k->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span x-text="openId === {{ $k->id }} ? 'Hide' : 'View more'"></span>
              </button>
            </div>
          </div>

          <div class="mt-4 anggota-list" x-show="openId === {{ $k->id }}" x-cloak x-ref="anggota-{{ $k->id }}">
            {{-- anggota will be loaded here via AJAX; fallback when no anggota --}}
            <div class="text-sm text-gray-500">Memuat...</div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-6">{{ $kepalas->links() }}</div>
  </div>
@endsection
