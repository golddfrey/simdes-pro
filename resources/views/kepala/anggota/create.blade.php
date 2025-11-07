@extends('layouts.windmill')

@section('title','Tambah Anggota Keluarga')

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-10">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Tambah Anggota Keluarga</h1>
            <p class="text-gray-600 mt-1">Isi data anggota keluarga dan ajukan untuk verifikasi admin.</p>
        </div>

        <div class="mx-auto bg-white shadow rounded-lg p-6">
                <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-700">Form Tambah Anggota</h2>
                </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('kepala.anggota.store') }}" class="space-y-4" id="form-anggota">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                    <input name="nama" value="{{ old('nama') }}" required class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <input name="nik" placeholder="Contoh: 320102xxxxxxxx" value="{{ old('nik') }}" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                    <p class="text-xs text-gray-500 mt-1">Masukkan 16 digit NIK sesuai KTP, tanpa spasi.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select name="provinsi" id="provinsi" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700"></select>
                    <p class="text-xs text-gray-500 mt-1">Pilih provinsi Indonesia.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih --</option>
                        <option value="Pria" {{ old('jenis_kelamin')=='Pria'?'selected':'' }}>Pria</option>
                        <option value="Wanita" {{ old('jenis_kelamin')=='Wanita'?'selected':'' }}>Wanita</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Agama</label>
                    <select name="agama" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih --</option>
                        <option value="Islam" {{ old('agama')=='Islam'?'selected':'' }}>Islam</option>
                        <option value="Kristen" {{ old('agama')=='Kristen'?'selected':'' }}>Kristen</option>
                        <option value="Katholik" {{ old('agama')=='Katholik'?'selected':'' }}>Katholik</option>
                        <option value="Hindu" {{ old('agama')=='Hindu'?'selected':'' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama')=='Buddha'?'selected':'' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama')=='Konghucu'?'selected':'' }}>Konghucu</option>
                        <option value="Lainnya" {{ old('agama')=='Lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pendidikan</label>
                    <input name="pendidikan" value="{{ old('pendidikan') }}" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input name="pekerjaan" value="{{ old('pekerjaan') }}" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Perkawinan</label>
                    <select name="status_perkawinan" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih --</option>
                        <option value="belum kawin" {{ old('status_perkawinan')=='belum kawin'?'selected':'' }}>Belum kawin</option>
                        <option value="kawin" {{ old('status_perkawinan')=='kawin'?'selected':'' }}>Kawin</option>
                        <option value="cerai" {{ old('status_perkawinan')=='cerai'?'selected':'' }}>Cerai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Dalam Keluarga</label>
                    <select name="status_dalam_keluarga" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-blue-400 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih --</option>
                        <option value="Istri" {{ old('status_dalam_keluarga')=='Istri'?'selected':'' }}>Istri</option>
                        <option value="Anak" {{ old('status_dalam_keluarga')=='Anak'?'selected':'' }}>Anak</option>
                        <option value="Keluarga Lainnya" {{ old('status_dalam_keluarga')=='Keluarga Lainnya'?'selected':'' }}>Keluarga Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                    <input name="kewarganegaraan" value="{{ old('kewarganegaraan', 'Indonesia') }}" readonly class="mt-1 block w-full rounded-md border border-gray-200 bg-gray-100 py-2 px-3 text-sm text-gray-700" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" maxlength="100" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700">{{ old('alamat') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1"><span id="alamat-count">{{ strlen(old('alamat', '')) }}</span>/100</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                    <select name="kota" id="kota" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelurahan</label>
                    <select name="kelurahan" id="kelurahan" class="mt-1 block w-full rounded-md border border-gray-200 bg-white py-2 px-3 text-sm text-gray-700"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}" readonly class="mt-1 block w-full rounded-md border border-gray-200 bg-gray-100 py-2 px-3 text-sm text-gray-700" />
                    <p class="text-xs text-gray-500 mt-1">Kode pos akan terisi otomatis setelah memilih kelurahan.</p>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Ajukan</button>
                <a href="{{ route('kepala.anggota.index') }}" class="ml-3 text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // alamat char count
    const alamat = document.getElementById('alamat');
    const alamatCount = document.getElementById('alamat-count');
    if (alamat) {
        alamat.addEventListener('input', function () { alamatCount.textContent = alamat.value.length; });
    }

    // load locations json
    const provSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const kecSelect = document.getElementById('kecamatan');
    const kelSelect = document.getElementById('kelurahan');
    const kodePosInput = document.getElementById('kode_pos');

    let locations = [];

    fetch('/js/indonesia-locations.json')
        .then(r => r.json())
        .then(data => {
            locations = data;
            provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
            data.forEach(p => provSelect.insertAdjacentHTML('beforeend', `<option value="${p.name}">${p.name}</option>`));

            // restore old values if present
            const oldProv = `{{ old('provinsi') }}`;
            const oldKota = `{{ old('kota') }}`;
            const oldKec = `{{ old('kecamatan') }}`;
            const oldKel = `{{ old('kelurahan') }}`;
            const oldKodepos = `{{ old('kode_pos') }}`;

            if (oldProv) { provSelect.value = oldProv; provSelect.dispatchEvent(new Event('change')); }
            if (oldKodepos) { kodePosInput.value = oldKodepos; }
            if (oldKota) { /* later set by change handlers */ }
        })
        .catch(() => { console.warn('Gagal memuat data lokasi.'); });

    provSelect.addEventListener('change', function () {
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        const prov = locations.find(p => p.name === this.value);
        if (!prov) return;
        prov.cities.forEach(c => kotaSelect.insertAdjacentHTML('beforeend', `<option value="${c.name}">${c.name}</option>`));
        const oldKota = `{{ old('kota') }}`;
        if (oldKota) { kotaSelect.value = oldKota; kotaSelect.dispatchEvent(new Event('change')); }
    });

    kotaSelect.addEventListener('change', function () {
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        const prov = locations.find(p => p.name === provSelect.value);
        if (!prov) return;
        const city = prov.cities.find(c => c.name === this.value);
        if (!city) return;
        city.kecamatan.forEach(k => kecSelect.insertAdjacentHTML('beforeend', `<option value="${k.name}">${k.name}</option>`));
        const oldKec = `{{ old('kecamatan') }}`;
        if (oldKec) { kecSelect.value = oldKec; kecSelect.dispatchEvent(new Event('change')); }
    });

    kecSelect.addEventListener('change', function () {
        kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        const prov = locations.find(p => p.name === provSelect.value);
        if (!prov) return;
        const city = prov.cities.find(c => c.name === kotaSelect.value);
        if (!city) return;
        const kec = city.kecamatan.find(k => k.name === this.value);
        if (!kec) return;
        kec.kelurahan.forEach(kel => kelSelect.insertAdjacentHTML('beforeend', `<option value="${kel.name}" data-kodepos="${kel.kode_pos}">${kel.name} (${kel.kode_pos})</option>`));
        const oldKel = `{{ old('kelurahan') }}`;
        if (oldKel) { kelSelect.value = oldKel; kelSelect.dispatchEvent(new Event('change')); }
    });

    kelSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const kode = opt ? opt.getAttribute('data-kodepos') : '';
        kodePosInput.value = kode || '';
    });

    // client-side NIK length check
    const nikInputHtml = document.querySelector('input[name="nik"]');
    if (nikInputHtml) {
        nikInputHtml.setAttribute('maxlength', '16');
        nikInputHtml.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0,16);
        });
    }

    // final client-side check before submit
    const form = document.getElementById('form-anggota');
    form.addEventListener('submit', function (e) {
        const nik = nikInputHtml ? nikInputHtml.value : '';
        if (nik && nik.length !== 16) {
            e.preventDefault();
            alert('NIK harus 16 digit (angka).');
            nikInputHtml.focus();
            return false;
        }

        // ensure kode pos present
        if (!kodePosInput.value) {
            e.preventDefault();
            alert('Silakan pilih kelurahan yang valid sehingga kode pos terisi.');
            return false;
        }
    });
});
</script>
@endsection
