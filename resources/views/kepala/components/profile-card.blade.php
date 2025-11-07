@props(['person', 'actions' => null])

<div class="w-full bg-white rounded-2xl shadow-xl overflow-hidden">
  <div class="md:flex">
    <div class="md:w-1/3 bg-gradient-to-b from-indigo-600 to-indigo-400 p-8 flex items-center justify-center">
      @php
        $name = $person->nama ?? ($person['nama'] ?? 'Unknown');
        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random&color=fff&size=256';
      @endphp
      <img src="{{ $avatarUrl }}" alt="avatar" class="h-32 w-32 rounded-full border-4 border-white shadow-md" />
    </div>

    <div class="md:w-2/3 p-6 md:p-8">
      <h2 class="text-2xl font-semibold text-gray-800">{{ $person->nama ?? ($person['nama'] ?? '-') }}</h2>
      <p class="text-sm text-gray-500 mt-1">NIK: <span class="font-medium text-gray-700">{{ $person->nik ?? ($person['nik'] ?? '-') }}</span></p>

      <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700">
        <div>
          <p class="text-xs text-gray-500">Tempat Lahir</p>
          <p class="font-medium">{{ $person->tempat_lahir ?? ($person['tempat_lahir'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Tanggal Lahir</p>
          <p class="font-medium">{{ isset($person->tanggal_lahir) ? optional($person->tanggal_lahir)->format('d-m-Y') : (isset($person['tanggal_lahir']) ? \Illuminate\Support\Carbon::parse($person['tanggal_lahir'])->format('d-m-Y') : '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Jenis Kelamin</p>
          <p class="font-medium">{{ isset($person->jenis_kelamin) ? ($person->jenis_kelamin === 'P' ? 'Perempuan' : ($person->jenis_kelamin === 'L' ? 'Laki-laki' : $person->jenis_kelamin)) : ($person['jenis_kelamin'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Agama</p>
          <p class="font-medium">{{ $person->agama ?? ($person['agama'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Pendidikan</p>
          <p class="font-medium">{{ $person->pendidikan ?? ($person['pendidikan'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Pekerjaan</p>
          <p class="font-medium">{{ $person->pekerjaan ?? ($person['pekerjaan'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Status Perkawinan</p>
          <p class="font-medium">{{ $person->status_perkawinan ?? ($person['status_perkawinan'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Status dalam Keluarga</p>
          <p class="font-medium">{{ $person->status_dalam_keluarga ?? ($person['status_dalam_keluarga'] ?? '-') }}</p>
        </div>

        <div>
          <p class="text-xs text-gray-500">Kewarganegaraan</p>
          <p class="font-medium">{{ $person->kewarganegaraan ?? ($person['kewarganegaraan'] ?? 'Indonesia') }}</p>
        </div>

        <div class="sm:col-span-2 md:col-span-3">
          <p class="text-xs text-gray-500">Alamat Lengkap</p>
          <p class="font-medium">{{ $person->alamat ?? ($person['alamat'] ?? '-') }}</p>
        </div>
      </div>

      @if($actions)
        <div class="mt-6">{!! $actions !!}</div>
      @endif
    </div>
  </div>
</div>
