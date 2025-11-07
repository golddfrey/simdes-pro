@extends('layouts.windmill')

@section('title','Feedback Masuk')

@section('content')
  <div class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-semibold mb-4">Feedback Masuk</h1>
    <div class="bg-white rounded shadow p-4">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-gray-600">
            <th class="p-2">#</th>
            <th class="p-2">Kepala</th>
            <th class="p-2">Pesan (preview)</th>
            <th class="p-2">Tanggal</th>
            <th class="p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($feedbacks as $fb)
            <tr class="border-t">
              <td class="p-2">{{ $fb->id }}</td>
              <td class="p-2">{{ $fb->kepala->nama ?? 'Anon' }}</td>
              <td class="p-2">{{ Str::limit($fb->message, 80) }}</td>
              <td class="p-2">{{ optional($fb->created_at)->format('d M Y H:i') }}</td>
              <td class="p-2"><a href="{{ route('admin.feedback.show', $fb->id) }}" class="text-indigo-600">Lihat</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="mt-4">{{ $feedbacks->links() }}</div>
    </div>
  </div>
@endsection
