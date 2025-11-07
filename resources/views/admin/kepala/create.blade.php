@extends('layouts.windmill')

@section('title','Tambah Kepala Keluarga')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8" x-data="kepalaForm()">
  <h2 class="text-2xl font-semibold mb-6">Tambah Kepala Keluarga</h2>

  @if($errors->any())
    <div class="mb-4 text-red-600">
      <ul>
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Define Alpine component function before Alpine evaluates x-data on the form -->
  <script>
    function kepalaForm(){
      return {
        query: '',
        suggestions: [],
        selectedNik: '',
        showModal: false,
        form: {
          nik: '', nama: '', tempat_lahir: '', tanggal_lahir: '', agama: '', jenis_kelamin: '', nomor_telepon: ''
        },
        search(){
          const q = this.query.trim();
          if (!q) { this.suggestions = []; return; }
          fetch(`{{ route('admin.penduduk.search') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => { this.suggestions = data; })
            .catch(err => { console.error(err); this.suggestions = []; });
        },
        selectSuggestion(s){
          // populate form fields from selected penduduk, except nomor_telepon
          this.form.nik = s.nik;
          this.form.nama = s.nama;
          this.form.tempat_lahir = s.tempat_lahir || '';
          // normalize tanggal_lahir to yyyy-mm-dd for <input type="date">
          let dateValue = '';
          if (s.tanggal_lahir) {
            try {
              // handle ISO datetime like 2007-11-22T00:00:00.000000Z
              if (String(s.tanggal_lahir).includes('T')) {
                const dt = new Date(s.tanggal_lahir);
                if (!isNaN(dt)) {
                  dateValue = dt.toISOString().slice(0,10);
                } else {
                  dateValue = String(s.tanggal_lahir).split('T')[0];
                }
              } else {
                // already date-like (YYYY-MM-DD)
                dateValue = s.tanggal_lahir;
              }
            } catch (e) {
              dateValue = String(s.tanggal_lahir).split('T')[0];
            }
          }
          this.form.tanggal_lahir = dateValue;
          this.form.agama = s.agama || '';
          this.form.jenis_kelamin = s.jenis_kelamin || '';
          this.form.nomor_telepon = '';
          this.selectedNik = s.nik;
          this.query = s.nik;
          this.suggestions = [];
          // also set input fields directly for server-side old() compatibility
          const nikInput = document.querySelector('input[name="nik"]'); if(nikInput) nikInput.value = s.nik;
          const namaInput = document.querySelector('input[name="nama"]'); if(namaInput) namaInput.value = s.nama;
          const tempatInput = document.querySelector('input[name="tempat_lahir"]'); if(tempatInput) tempatInput.value = s.tempat_lahir || '';
          const tglInput = document.querySelector('input[name="tanggal_lahir"]'); if(tglInput) tglInput.value = dateValue;
          const agamaInput = document.querySelector('input[name="agama"]'); if(agamaInput) agamaInput.value = s.agama || '';
          const jkSelect = document.querySelector('select[name="jenis_kelamin"]'); if(jkSelect && s.jenis_kelamin) jkSelect.value = s.jenis_kelamin;
        },
        confirmSave(){
          // copy current form values from DOM into alpine form state
          const nik = document.querySelector('input[name="nik"]');
          const nama = document.querySelector('input[name="nama"]');
          const tempat = document.querySelector('input[name="tempat_lahir"]');
          const tgl = document.querySelector('input[name="tanggal_lahir"]');
          const agama = document.querySelector('input[name="agama"]');
          const jk = document.querySelector('select[name="jenis_kelamin"]');
          const tel = document.querySelector('input[name="nomor_telepon"]');
          this.form.nik = nik ? nik.value : '';
          this.form.nama = nama ? nama.value : '';
          this.form.tempat_lahir = tempat ? tempat.value : '';
          this.form.tanggal_lahir = tgl ? tgl.value : '';
          this.form.agama = agama ? agama.value : '';
          this.form.jenis_kelamin = jk ? jk.value : '';
          this.form.nomor_telepon = tel ? tel.value : '';
          this.showModal = true;
        },
        submitForm(){
          this.showModal = false;
          // finally submit the form
          document.getElementById('kepala-form').submit();
        }
      }
    }
  </script>

  <form id="kepala-form" action="{{ route('admin.kepala.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid gap-4">
      <div x-data class="relative">
        <label class="block text-sm">NIK</label>
        <!-- input used for typing and selecting NIK; suggestions appear in dropdown -->
        <input id="nik-input" x-model="query" @input.debounce.300="search()" type="text" name="nik" value="{{ old('nik') }}" class="w-full border rounded px-3 py-2" placeholder="Ketik NIK atau nama untuk mencari" autocomplete="off" required>
        <input type="hidden" name="selected_nik" x-model="selectedNik">

        <ul x-show="suggestions.length > 0" x-cloak class="absolute z-50 left-0 right-0 bg-white border rounded mt-1 max-h-60 overflow-auto">
          <template x-for="s in suggestions" :key="s.nik">
            <li @click.prevent="selectSuggestion(s)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
              <div class="font-medium" x-text="s.nama + ' — ' + s.nik"></div>
              <div class="text-xs text-gray-500" x-text="s.tempat_lahir + (s.tanggal_lahir ? (' • ' + s.tanggal_lahir) : '')"></div>
            </li>
          </template>
        </ul>
      </div>
      <div>
        <label class="block text-sm">Nama</label>
        <input id="nama" type="text" name="nama" x-model="form.nama" :readonly="selectedNik !== ''" class="w-full border rounded px-3 py-2" required>
      </div>
      <div>
        <label class="block text-sm">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" :readonly="selectedNik !== ''" class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" :readonly="selectedNik !== ''" class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm">Agama</label>
        <input type="text" name="agama" x-model="form.agama" :readonly="selectedNik !== ''" class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm">Jenis Kelamin</label>
        <select name="jenis_kelamin" x-model="form.jenis_kelamin" :disabled="selectedNik !== ''" class="w-full border rounded px-3 py-2">
          <option value="">-- Pilih --</option>
          <option value="L">Laki-laki</option>
          <option value="P">Perempuan</option>
        </select>
      </div>
      <div>
        <label class="block text-sm">Nomor Telepon</label>
        <input id="nomor_telepon" type="text" name="nomor_telepon" x-model="form.nomor_telepon" class="w-full border rounded px-3 py-2">
      </div>

      <div class="flex items-center space-x-3">
        <button type="button" @click="confirmSave()" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
        <a href="{{ route('admin.kepala.index') }}" class="px-4 py-2 border rounded">Batal</a>
      </div>
    </div>
  </form>

  <!-- Confirmation modal -->
  <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
    <!-- softer backdrop -->
    <div @click="showModal=false" x-show="showModal" x-transition.opacity class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-sm"></div>

    <!-- card-style modal -->
    <div x-show="showModal" x-transition.origin.center class="relative bg-white rounded-lg shadow-2xl w-full max-w-lg p-6 transform transition-transform">
      <!-- close button -->
      <button @click="showModal=false" aria-label="Tutup" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <h3 class="text-lg font-semibold mb-3">Konfirmasi Data Kepala Keluarga</h3>
      <div class="space-y-2 text-sm text-gray-700">
        <div><strong>NIK:</strong> <span x-text="form.nik"></span></div>
        <div><strong>Nama:</strong> <span x-text="form.nama"></span></div>
        <div><strong>Tempat, Tanggal Lahir:</strong> <span x-text="(form.tempat_lahir || '-') + (form.tanggal_lahir ? (' , '+form.tanggal_lahir) : '')"></span></div>
        <div><strong>Agama:</strong> <span x-text="form.agama || '-'"></span></div>
        <div><strong>Jenis Kelamin:</strong> <span x-text="form.jenis_kelamin == 'L' ? 'Laki-laki' : (form.jenis_kelamin == 'P' ? 'Perempuan' : '-')"></span></div>
        <div><strong>Nomor Telepon:</strong> <span x-text="form.nomor_telepon || '-'"></span></div>
      </div>

      <div class="mt-4 flex justify-end space-x-2">
        <button class="px-4 py-2 border rounded text-sm" @click="showModal=false">Batal</button>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded text-sm" @click="submitForm()">Konfirmasi & Simpan</button>
      </div>
    </div>
  </div>

  
</div>
@endsection
