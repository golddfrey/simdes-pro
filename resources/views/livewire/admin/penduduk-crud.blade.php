<div class="w-full mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center space-x-3">
      <h2 class="text-2xl font-semibold">Data Penduduk</h2>
      @if(isset($paginationMode))
        @php
          $badgeText = 'Mode: Unknown';
          $badgeClasses = 'bg-gray-200 text-gray-700';
          if ($paginationMode === 'keyset') {
              $badgeText = 'Keyset (cursor)';
              $badgeClasses = 'bg-green-100 text-green-800';
          } elseif ($paginationMode === 'numbered') {
              $badgeText = 'Paginated (numbered)';
              $badgeClasses = 'bg-blue-100 text-blue-800';
          } elseif ($paginationMode === 'simple') {
              $badgeText = 'Simple (next/prev)';
              $badgeClasses = 'bg-yellow-100 text-yellow-800';
          }
        @endphp
        <span class="px-2 py-1 rounded text-sm font-medium {{ $badgeClasses }}">{{ $badgeText }}</span>
      @endif
    </div>

    <!-- Controls: search, per-page selector, add button -->
  <div class="flex items-center space-x-3">
  <!-- Live search: gunakan wire:input agar setiap karakter memicu update Livewire (lebih longgar debounce untuk mengurangi request) -->
  <input type="text" wire:input.debounce.500ms="$set('search', $event.target.value)" placeholder="Cari nama, NIK, alamat..." class="border rounded px-3 py-2" />

      <!-- Per-page selector: 10/50/100 -->
      <!-- perPage: gunakan wire:change untuk memastikan perubahan langsung dikirim ke server -->
      <select wire:change="$set('perPage', $event.target.value)" class="border rounded px-2 py-2">
        @foreach($perPageOptions as $opt)
          <option value="{{ $opt }}">{{ $opt }} / page</option>
        @endforeach
      </select>

      <button wire:click="create" class="px-3 py-2 bg-indigo-600 text-white rounded">Tambah Penduduk</button>
    </div>
  </div>

  @if(session()->has('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
  @endif
  @if($savedMessage)
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">{{ $savedMessage }}</div>
  @endif

  <!-- Detail modal -->
  @if($showDetail && $detailPenduduk)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
      <div class="absolute inset-0 bg-black bg-opacity-25" wire:click="$set('showDetail', false)"></div>
      <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-3xl p-6">
        <h3 class="text-lg font-semibold mb-3">Detail Penduduk</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          @foreach($detailPenduduk as $key => $val)
            <div>
              <div class="text-xs text-gray-500 uppercase">{{ str_replace('_',' ', $key) }}</div>
              <div class="text-sm">{{ $val }}</div>
            </div>
          @endforeach
        </div>
        <div class="mt-4 text-right">
          <button wire:click="$set('showDetail', false)" class="px-3 py-1 border rounded">Tutup</button>
        </div>
      </div>
    </div>
  @endif

  <div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 table-auto">
      <thead class="bg-gray-50">
        <tr>
          <!-- Sortable column headers. Clicking toggles sort direction. -->
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortByColumn('nik')">
            NIK
            @if($sortBy === 'nik')
              <span class="text-xs">({{ $sortDir }})</span>
            @endif
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortByColumn('nama')">
            Nama
            @if($sortBy === 'nama')
              <span class="text-xs">({{ $sortDir }})</span>
            @endif
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortByColumn('umur')">
            Umur
            @if($sortBy === 'umur')
              <span class="text-xs">({{ $sortDir }})</span>
            @endif
          </th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
          <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>

        <!-- Filtering row: filter by age range -->
        <tr class="bg-gray-100">
          <th class="px-4 py-2"></th>
          <th class="px-4 py-2"></th>
          <th class="px-4 py-2"></th>
          <th class="px-4 py-2"></th>
          <th class="px-4 py-2">
            <!-- Age filter: min / max (years) -->
            <div class="flex items-center space-x-2">
              <input type="number" min="0" wire:input.debounce.500ms="$set('ageMin', $event.target.value)" placeholder="Min" class="w-20 border rounded px-2 py-1 text-sm" />
              <span class="text-sm">-</span>
              <input type="number" min="0" wire:input.debounce.500ms="$set('ageMax', $event.target.value)" placeholder="Max" class="w-20 border rounded px-2 py-1 text-sm" />
            </div>
          </th>
          <th class="px-4 py-2"></th>
          <th class="px-4 py-2 text-right">
            <!-- Clear filters button -->
            <button wire:click="resetFilters" class="text-sm text-gray-600">Reset</button>
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        @foreach($penduduks as $p)
          <tr>
            @php
              // Support multiple paginator types:
              // - LengthAwarePaginator / Paginator provide currentPage()/perPage()
              // - CursorPaginator does not provide currentPage(), so fall back to
              //   a simple per-page relative index (1..N) to avoid errors.
              if (method_exists($penduduks, 'currentPage') && method_exists($penduduks, 'perPage')) {
                  $rowNumber = ($penduduks->currentPage() - 1) * $penduduks->perPage() + $loop->iteration;
              } else {
                  $rowNumber = $loop->iteration;
              }
            @endphp
            <td class="px-4 py-3 text-sm text-gray-700">{{ $rowNumber }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->nik }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->nama }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ optional($p->tanggal_lahir) ? \Illuminate\Support\Carbon::parse($p->tanggal_lahir)->format('Y-m-d') : '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-700">{{ $p->tanggal_lahir ? \Illuminate\Support\Carbon::parse($p->tanggal_lahir)->age : '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($p->alamat, 80) }}</td>
            <td class="px-4 py-3 text-sm text-right">
        <!-- Call component methods directly. Note: component method for detail is renamed to openDetail to avoid property/method name collision. -->
        <button wire:click="openDetail({{ $p->id }})" class="px-3 py-1 border rounded text-sm mr-2">Detail</button>
        <button wire:click="edit({{ $p->id }})" class="px-3 py-1 border rounded text-sm">Edit</button>
        <button wire:click="confirmDelete({{ $p->id }})" class="px-3 py-1 border rounded text-sm text-red-600">Hapus</button>
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
            <input type="text" wire:model="form.nik" class="w-full border rounded px-3 py-2" @if($editingId) readonly @endif>
            @error('form.nik') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="block text-sm">Nama</label>
            <input type="text" wire:model="form.nama" class="w-full border rounded px-3 py-2">
            @error('form.nama') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="block text-sm">Tempat Lahir</label>
            <input type="text" wire:model="form.tempat_lahir" class="w-full border rounded px-3 py-2">
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
          <div>
            <label class="block text-sm">Agama</label>
            <select wire:model="form.agama" class="w-full border rounded px-3 py-2">
              <option value="">-- Pilih --</option>
              @foreach($agamaOptions as $agama)
                <option value="{{ $agama }}">{{ $agama }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm">Status Perkawinan</label>
            <select wire:model="form.status_perkawinan" class="w-full border rounded px-3 py-2">
              <option value="">-- Pilih --</option>
              @foreach($statusOptions as $st)
                <option value="{{ $st }}">{{ $st }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm">Pekerjaan</label>
            <select wire:model="form.pekerjaan" class="w-full border rounded px-3 py-2">
              <option value="">-- Pilih --</option>
              @foreach($pekerjaanOptions as $pe)
                <option value="{{ $pe }}">{{ $pe }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm">Nomor Telepon</label>
            <input type="text" wire:model="form.nomor_telepon" class="w-full border rounded px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm">Alamat</label>
            <input type="text" wire:model="form.alamat" class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm">Kota</label>
            <input type="text" wire:model="form.kota" class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm">Kecamatan</label>
            <input type="text" wire:model="form.kecamatan" class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm">Kelurahan</label>
            <input type="text" wire:model="form.kelurahan" class="w-full border rounded px-3 py-2">
          </div>
        </div>

        <div class="mt-4 flex justify-end space-x-2">
          <button wire:click="$set('showForm', false)" class="px-3 py-1 border rounded">Batal</button>
          <button wire:click="confirmSave" class="px-3 py-1 bg-indigo-600 text-white rounded">Simpan</button>
        </div>
      </div>
    </div>
  @endif

  <!-- Save confirmation modal -->
  @if($confirmingSave)
    <div class="fixed inset-0 flex items-center justify-center px-4 py-6" style="z-index:9999;">
      <div class="absolute inset-0 bg-black bg-opacity-25" wire:click="$set('confirmingSave', false)"></div>
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h4 class="text-lg font-semibold mb-2">Konfirmasi Simpan</h4>
        <p class="text-sm text-gray-700">Apakah Anda yakin ingin menyimpan perubahan data penduduk?</p>
        <div class="mt-4 flex justify-end space-x-2">
          <button wire:click="$set('confirmingSave', false)" class="px-3 py-1 border rounded">Batal</button>
          <button wire:click="confirmAndSave" class="px-3 py-1 bg-indigo-600 text-white rounded">Ya, Simpan</button>
        </div>
      </div>
    </div>
  @endif

  <!-- Delete confirmation modal -->
  @if($confirmingDeleteId)
    <div class="fixed inset-0 flex items-center justify-center px-4 py-6" style="z-index:9999;">
      <div class="absolute inset-0 bg-black bg-opacity-25" wire:click="$set('confirmingDeleteId', null)"></div>
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h4 class="text-lg font-semibold mb-2">Konfirmasi Hapus</h4>
        <p class="text-sm text-gray-700">Anda yakin ingin menghapus data penduduk ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="mt-4 flex justify-end space-x-2">
          <button wire:click="$set('confirmingDeleteId', null)" class="px-3 py-1 border rounded">Batal</button>
          <button wire:click="delete({{ $confirmingDeleteId }})" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
        </div>
      </div>
    </div>
  @endif

</div>
