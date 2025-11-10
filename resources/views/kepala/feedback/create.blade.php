@extends('layouts.windmill')

@section('title','Kritik & Saran')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
  <h2 class="text-xl font-semibold mb-4">Kritik dan Saran</h2>
  <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded text-sm text-gray-700">
    <p>Gunakan formulir ini untuk menyampaikan masukan yang konstruktif tentang layanan, proses, atau fitur yang perlu diperbaiki. Jelaskan masalah yang Anda alami, sertakan contoh atau langkah untuk mereproduksi bila perlu, dan usulkan solusi apabila memungkinkan. Semua masukan akan ditinjau oleh tim kami untuk perbaikan berkelanjutan.</p>
    <p class="mt-2 text-xs text-gray-500">Opsional: tambahkan kontak jika Anda menginginkan tanggapan balik.</p>
  </div>

  <form method="POST" action="{{ route('kepala.feedback.store') }}">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-700">Pesan</label>
      <textarea name="message" rows="6" required class="mt-1 block w-full border border-gray-200 rounded p-3">{{ old('message') }}</textarea>
    </div>

    <div class="mt-4">
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Kirim</button>
    </div>
  </form>
</div>
@endsection
