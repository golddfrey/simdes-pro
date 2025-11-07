@extends('layouts.windmill')

@section('title','Permintaan Perubahan Anggota')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-8">
        <h1 class="text-2xl font-semibold mb-4">Permintaan Perubahan Anggota (Pending)</h1>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kepala Keluarga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ringkasan Payload</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diajukan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($requests as $r)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $r->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($r->action) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ optional($r->kepala)->nama ?? ('#'.$r->kepala_keluarga_id) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">@php $p = is_array($r->payload) ? $r->payload : json_decode($r->payload, true); echo e(collect($p ?? [])->take(3)->map(function($v,$k){ return $k.': '.$v; })->join(', ')); @endphp</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a href="{{ route('admin.anggota.requests.show', $r->id) }}" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-gray-700 rounded-md shadow-sm hover:bg-gray-50 text-sm">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($requests->isEmpty())
            <div class="mt-6 text-gray-500">Tidak ada permintaan pending saat ini.</div>
        @endif
    </div>
@endsection
