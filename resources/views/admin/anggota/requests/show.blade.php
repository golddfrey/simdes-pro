@extends('layouts.windmill')

@section('title', 'Detail Permintaan Anggota')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="bg-white rounded shadow p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Permintaan #{{ $req->id }}</h2>
                    <p class="text-sm text-gray-500">Aksi: <span class="font-medium text-gray-700">{{ ucfirst($req->action) }}</span></p>
                    <p class="text-sm text-gray-500">Kepala Keluarga: <span class="font-medium text-gray-700">{{ optional($req->kepala)->nama ?? ('#'.$req->kepala_keluarga_id) }}</span></p>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.anggota.requests.index') }}" class="px-3 py-1 border rounded text-sm">Kembali</a>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="text-md font-semibold mb-2">Ringkasan Payload</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $payload = is_array($req->payload) ? $req->payload : json_decode($req->payload, true); @endphp
                    @if(!empty($payload))
                        @foreach($payload as $k=>$v)
                            <div class="bg-gray-50 p-3 rounded">
                                <div class="text-xs text-gray-500">{{ $k }}</div>
                                <div class="font-medium text-gray-800">{{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-gray-500">Tidak ada data.</div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <form method="post" action="{{ route('admin.anggota.requests.approve', $req->id) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Setujui</button>
                </form>

                <button @click="document.getElementById('reject-box').classList.toggle('hidden')" class="px-4 py-2 border rounded text-sm">Tolak</button>

                <div id="reject-box" class="hidden">
                    <form method="post" action="{{ route('admin.anggota.requests.reject', $req->id) }}">
                        @csrf
                        <label class="block text-sm mb-1">Alasan penolakan</label>
                        <textarea name="reason" class="w-full border rounded px-3 py-2 mb-2" rows="3"></textarea>
                        <div class="flex space-x-2">
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Kirim Penolakan</button>
                            <button type="button" onclick="document.getElementById('reject-box').classList.add('hidden')" class="px-3 py-1 border rounded">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
