@if($anggota->isEmpty())
  <div class="p-3 text-sm text-gray-500">Belum ada anggota keluarga.</div>
@else
  <ul class="mt-2 space-y-2">
    @foreach($anggota as $a)
      <li class="border rounded p-2">
        <div class="flex justify-between">
          <div>
            <div class="font-medium">{{ $a->nama }}</div>
            <div class="text-xs text-gray-500">NIK: {{ $a->nik ?? '-' }}</div>
            <div class="text-xs text-gray-500">Hub: {{ $a->status_dalam_keluarga ?? '-' }}</div>
          </div>
          <div class="text-xs text-gray-500">{{ optional($a->created_at)->format('d M Y') }}</div>
        </div>
      </li>
    @endforeach
  </ul>
@endif
