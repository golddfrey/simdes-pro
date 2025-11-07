@extends('layouts.windmill')

@section('title','Kartu Keluarga')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 bg-white">
  <div class="text-right mb-4">
    <button onclick="window.print()" class="px-3 py-2 bg-green-600 text-white rounded">Cetak / Simpan PDF</button>
  </div>

  <h2 class="text-xl font-semibold mb-2">Kartu Keluarga</h2>
  <div class="mb-4">
    <p><strong>Nama Kepala Keluarga:</strong> {{ $kepala->nama }}</p>
    <p><strong>NIK:</strong> {{ $kepala->nik }}</p>
    <p><strong>Alamat:</strong> {{ $alamat ?? '-' }}</p>
  </div>

  <table class="w-full border-collapse border" border="1">
    <thead>
      <tr class="bg-gray-100">
        <th class="p-2">Foto</th>
        <th class="p-2">Nama</th>
        <th class="p-2">NIK</th>
        <th class="p-2">Tempat, Tanggal Lahir</th>
        <th class="p-2">Hubungan</th>
        <th class="p-2">Agama</th>
      </tr>
    </thead>
    <tbody>
      @foreach($anggota as $a)
      <tr>
        <td class="p-2 text-center"><img src="{{ $a->jenis_kelamin === 'P' ? 'https://ui-avatars.com/api/?name=Jane+Doe&background=random&color=fff&size=64' : 'https://ui-avatars.com/api/?name=John+Doe&background=random&color=fff&size=64' }}" class="inline-block" alt="avatar"/></td>
        <td class="p-2">{{ $a->nama }}</td>
        <td class="p-2">{{ $a->nik ?? '-' }}</td>
        <td class="p-2">{{ $a->tempat_lahir ?? '-' }}, {{ optional($a->tanggal_lahir)->format('d-m-Y') ?? '-' }}</td>
        <td class="p-2">{{ $a->status_dalam_keluarga ?? '-' }}</td>
        <td class="p-2">{{ $a->agama ?? '-' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
