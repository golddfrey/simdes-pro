<div class="max-w-6xl mx-auto px-6 py-8">
  <h1 class="text-2xl font-semibold mb-4">Permintaan Perubahan Anggota (Pending)</h1>

  @if(session()->has('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
  @endif

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
              <button wire:click="openDetail({{ $r->id }})" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-gray-700 rounded-md shadow-sm hover:bg-gray-50 text-sm">Lihat</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $requests->links() }}</div>

  <!-- Detail modal/card -->
  @if($showDetail && $selectedId)
    @php $req = \App\Models\AnggotaKeluargaChangeRequest::find($selectedId); @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
      <div class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-sm" wire:click="closeDetail"></div>
      <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-3xl p-6">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-lg font-semibold">Permintaan #{{ $req->id }}</h3>
            <p class="text-sm text-gray-500">Aksi: {{ ucfirst($req->action) }}</p>
            <p class="text-sm text-gray-500">Kepala: {{ optional($req->kepala)->nama ?? ('#'.$req->kepala_keluarga_id) }}</p>
          </div>
          <div class="text-right">
            <button wire:click="closeDetail" class="px-3 py-1 border rounded">Tutup</button>
          </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
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

        <div class="mt-6 flex items-center space-x-3">
          <button wire:click="approve({{ $req->id }})" class="px-4 py-2 bg-green-600 text-white rounded">Setujui</button>
          <button class="px-4 py-2 border rounded" onclick="document.getElementById('reject-box-live').classList.toggle('hidden')">Tolak</button>

          <div id="reject-box-live" class="hidden">
            <textarea wire:model="rejectReason" class="w-full border rounded px-3 py-2 mt-2" rows="3" placeholder="Alasan penolakan"></textarea>
            <div class="mt-2">
              <button wire:click="reject({{ $req->id }})" class="px-3 py-1 bg-red-600 text-white rounded">Kirim Penolakan</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

</div>
