@extends('layouts.windmill')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-6xl mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold">Notifikasi</h2>
    <div class="flex items-center space-x-2">
      <form method="GET" class="flex" action="{{ url()->current() }}">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari..." class="border px-2 py-1 rounded-l">
        <button class="px-3 py-1 bg-indigo-600 text-white rounded-r">Cari</button>
      </form>
      <form method="POST" action="{{ $role === 'admin' ? route('admin.notifications.mark_all_read') : route('kepala.notifications.mark_all_read') }}">
        @csrf
        <button class="px-3 py-1 bg-gray-100 rounded border text-sm">Tandai semua sebagai dibaca</button>
      </form>
    </div>
  </div>

  <div class="mb-3 text-sm text-gray-600">Filter:
    <a href="?filter=all" class="ml-2 {{ $filter === 'all' ? 'font-semibold' : '' }}">Semua</a>
    <a href="?filter=unread" class="ml-2 {{ $filter === 'unread' ? 'font-semibold' : '' }}">Belum dibaca</a>
    <a href="?filter=read" class="ml-2 {{ $filter === 'read' ? 'font-semibold' : '' }}">Terbaca</a>
  </div>

  <div class="bg-white border rounded">
    @if($notifications->isEmpty())
      <div class="p-4 text-sm text-gray-600">Tidak ada notifikasi.</div>
    @else
      <table class="w-full">
        <thead class="text-left text-xs text-gray-500 uppercase">
          <tr>
            <th class="p-2">Judul</th>
            <th class="p-2">Isi</th>
            <th class="p-2">Waktu</th>
            <th class="p-2">Status</th>
            <th class="p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($notifications as $n)
            @php $data = (array) $n->data; @endphp
            <tr class="border-t">
              <td class="p-2 font-medium">{{ $data['title'] ?? 'Notifikasi' }}</td>
              <td class="p-2 text-sm text-gray-600">{{ $data['body'] ?? '' }}</td>
              <td class="p-2 text-sm text-gray-500">{{ $n->created_at->format('Y-m-d H:i') }}<br><span class="text-xs">{{ $n->created_at->diffForHumans() }}</span></td>
              <td class="p-2 text-sm">@if($n->read_at) <span class="text-green-600">Terbaca</span> @else <span class="text-red-600">Belum</span> @endif</td>
              <td class="p-2 text-sm">
                <a href="{{ $role === 'admin' ? route('admin.notifications.go', $n->id) : route('kepala.notifications.go', $n->id) }}" class="text-indigo-600">Buka</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="p-3">{{ $notifications->links() }}</div>
    @endif
  </div>
</div>
@endsection
