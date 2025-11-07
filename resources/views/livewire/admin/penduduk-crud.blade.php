<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-semibold">Data Penduduk</h2>
    <div class="flex items-center space-x-2">
      <input type="text" wire:model.debounce.300ms="search" placeholder="Cari nama, NIK, alamat..." class="border rounded px-3 py-2" />
      <button wire:click="create" class="px-3 py-2 bg-indigo-600 text-white rounded">Tambah Penduduk</button>
    </div>
  </div>

  @if(session()->has('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Kelamin</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
          <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        @foreach($penduduks as $p)
          <tr>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->nik }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->nama }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->jenis_kelamin }}</td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($p->alamat, 60) }}</td>
            <td class="px-4 py-3 text-sm text-right">
              <button wire:click="edit({{ $p->id }})" class="px-3 py-1 border rounded text-sm">Edit</button>
              <button wire:click="delete({{ $p->id }})" class="px-3 py-1 border rounded text-sm text-red-600">Hapus</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $penduduks->links() }}</div>

  <!-- Form modal -->
  @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
      <div class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
      <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-2xl p-6">
        <h3 class="text-lg font-semibold mb-3">{{ $editingId ? 'Edit Penduduk' : 'Tambah Penduduk' }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm">NIK</label>
            <input type="text" wire:model="form.nik" class="w-full border rounded px-3 py-2">
            @error('form.nik') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="block text-sm">Nama</label>
            <input type="text" wire:model="form.nama" class="w-full border rounded px-3 py-2">
            @error('form.nama') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="block text-sm">Jenis Kelamin</label>
            <select wire:model="form.jenis_kelamin" class="w-full border rounded px-3 py-2">
              <option value="">-- Pilih --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm">Tanggal Lahir</label>
            <input type="date" wire:model="form.tanggal_lahir" class="w-full border rounded px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm">Alamat</label>
            <input type="text" wire:model="form.alamat" class="w-full border rounded px-3 py-2">
          </div>
        </div>

        <div class="mt-4 flex justify-end space-x-2">
          <button wire:click="$set('showForm', false)" class="px-3 py-1 border rounded">Batal</button>
          <button wire:click="save" class="px-3 py-1 bg-indigo-600 text-white rounded">Simpan</button>
        </div>
      </div>
    </div>
  @endif

</div>
