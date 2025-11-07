@extends('layouts.windmill')

@section('title','Feedback Detail')

@section('content')
  <div class="max-w-4xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-semibold mb-4">Feedback Detail</h1>
    <div class="bg-white rounded shadow p-6">
      <div class="mb-4">
        <div class="text-sm text-gray-500">Dari</div>
        <div class="font-medium">{{ $fb->kepala->nama ?? 'Anon' }} (ID: {{ $fb->kepala_keluarga_id }})</div>
        <div class="text-xs text-gray-500">{{ optional($fb->created_at)->format('d M Y H:i') }}</div>
      </div>
      <div class="mt-4">
        <h3 class="font-semibold">Pesan</h3>
        <div class="mt-2 text-sm text-gray-700 whitespace-pre-wrap">{{ $fb->message }}</div>
      </div>
    </div>
  </div>
@endsection
