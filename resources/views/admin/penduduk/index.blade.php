@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
  <h2 class="text-2xl font-semibold mb-6">Data Seluruh Penduduk</h2>

  <section class="mb-8">
    <h3 class="text-lg font-medium mb-2">Kepala Keluarga</h3>
    <table class="w-full bg-white rounded shadow">
      <thead>
        <tr class="text-left">
          <th class="p-3">NIK</th>
          <th class="p-3">Nama</th>
          <th class="p-3">Alamat</th>
        </tr>
      </thead>
      <tbody>
        @foreach($kepalas as $k)
        <tr class="border-t">
          <td class="p-3">{{ $k->nik }}</td>
          <td class="p-3">{{ $k->nama }}</td>
          <td class="p-3">{{ $k->alamat ?? '-' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-4">{{ $kepalas->links() }}</div>
  </section>

  <section>
    <h3 class="text-lg font-medium mb-2">Anggota Keluarga</h3>
    <table class="w-full bg-white rounded shadow">
      <thead>
        <tr class="text-left">
          <th class="p-3">NIK</th>
          <th class="p-3">Nama</th>
          <th class="p-3">Hubungan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($anggota as $a)
        <tr class="border-t">
          <td class="p-3">{{ $a->nik }}</td>
          <td class="p-3">{{ $a->nama }}</td>
          <td class="p-3">{{ $a->status_dalam_keluarga ?? '-' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-4">{{ $anggota->links() }}</div>
  </section>
</div>
@endsection
