@extends('layouts.windmill')

@section('title','Kritik & Saran')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">
  <h2 class="text-xl font-semibold mb-4">Kritik dan Saran</h2>

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
