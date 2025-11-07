<div class="max-w-3xl mx-auto px-6 py-8">
  <h2 class="text-2xl font-semibold mb-6">Tambah Kepala Keluarga</h2>

  @if(session()->has('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
  @endif

  <form wire:submit.prevent="confirmSave" class="bg-white p-6 rounded shadow">
    <div class="grid gap-4">
      <div class="relative">
        <label class="block text-sm">NIK</label>
        <input
          type="text"
          wire:model.debounce.300ms="query"
          wire:keydown.arrow-down.prevent="highlightNext"
          wire:keydown.arrow-up.prevent="highlightPrev"
          wire:keydown.enter.prevent="selectHighlighted"
          class="w-full border rounded px-3 py-2"
          placeholder="Ketik NIK atau nama untuk mencari"
          autocomplete="off"
          role="combobox"
          aria-expanded="{{ !empty($suggestions) ? 'true' : 'false' }}"
        >
        <input type="hidden" wire:model="form.nik">

        @if(!empty($suggestions))
          <ul class="absolute z-50 left-0 right-0 bg-white border rounded mt-1 max-h-60 overflow-auto" role="listbox">
            @foreach($suggestions as $idx => $s)
              @php $isHighlight = ($highlight === $idx); @endphp
              <li
                wire:click.prevent="selectSuggestion('{{ $s['nik'] }}')"
                wire:key="suggestion-{{ $s['nik'] }}"
                class="px-4 py-2 cursor-pointer {{ $isHighlight ? 'bg-gray-100' : 'hover:bg-gray-100' }}"
                role="option"
                aria-selected="{{ $isHighlight ? 'true' : 'false' }}"
              >
                <div class="font-medium">{{ $s['nama'] }} — {{ $s['nik'] }}</div>
                <div class="text-xs text-gray-500">{{ $s['tempat_lahir'] ?? '-' }}{{ !empty($s['tanggal_lahir']) ? ' • '.$s['tanggal_lahir'] : '' }}</div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <div>
        <label class="block text-sm">Nama</label>
        <input type="text" wire:model.defer="form.nama" class="w-full border rounded px-3 py-2" @if($selectedNik) readonly @endif required>
      </div>

      <div>
        <label class="block text-sm">Tempat Lahir</label>
        <input type="text" wire:model.defer="form.tempat_lahir" class="w-full border rounded px-3 py-2" @if($selectedNik) readonly @endif>
      </div>

      <div>
        <label class="block text-sm">Tanggal Lahir</label>
        <input type="date" wire:model.defer="form.tanggal_lahir" class="w-full border rounded px-3 py-2" @if($selectedNik) readonly @endif>
      </div>

      <div>
        <label class="block text-sm">Agama</label>
        <input type="text" wire:model.defer="form.agama" class="w-full border rounded px-3 py-2" @if($selectedNik) readonly @endif>
      </div>

      <div>
        <label class="block text-sm">Jenis Kelamin</label>
        <select wire:model.defer="form.jenis_kelamin" class="w-full border rounded px-3 py-2" @if($selectedNik) disabled @endif>
          <option value="">-- Pilih --</option>
          <option value="L">Laki-laki</option>
          <option value="P">Perempuan</option>
        </select>
      </div>

      <div>
        <label class="block text-sm">Nomor Telepon</label>
        <input type="text" wire:model.defer="form.nomor_telepon" class="w-full border rounded px-3 py-2">
      </div>

      <div class="flex items-center space-x-3">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
        <a href="{{ route('admin.kepala.index') }}" class="px-4 py-2 border rounded">Batal</a>
      </div>
    </div>
  </form>

  {{-- Confirmation modal rendered server-side by Livewire --}}
  @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
      <div class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
      <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-lg p-6">
        <div class="absolute top-3 right-3">
          <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <h3 class="text-lg font-semibold mb-3">Konfirmasi Data Kepala Keluarga</h3>
        <div class="space-y-2 text-sm text-gray-700">
          <div><strong>NIK:</strong> {{ $form['nik'] }}</div>
          <div><strong>Nama:</strong> {{ $form['nama'] }}</div>
          <div><strong>Tempat, Tanggal Lahir:</strong> {{ ($form['tempat_lahir'] ?: '-') }}{{ $form['tanggal_lahir'] ? ' , '.$form['tanggal_lahir'] : '' }}</div>
          <div><strong>Agama:</strong> {{ $form['agama'] ?: '-' }}</div>
          <div><strong>Jenis Kelamin:</strong> {{ $form['jenis_kelamin'] == 'L' ? 'Laki-laki' : ($form['jenis_kelamin'] == 'P' ? 'Perempuan' : '-') }}</div>
          <div><strong>Nomor Telepon:</strong> {{ $form['nomor_telepon'] ?: '-' }}</div>
        </div>
        <div class="mt-4 flex justify-end space-x-2">
          <button wire:click="$set('showModal', false)" class="px-4 py-2 border rounded">Batal</button>
          <button wire:click="save" class="px-4 py-2 bg-indigo-600 text-white rounded">Konfirmasi & Simpan</button>
        </div>
      </div>
    </div>
  @endif

</div>
