@extends('layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700">Edit Anggota Keluarga</h2>
            <p class="text-sm text-gray-500">Ajukan perubahan data anggota keluarga untuk diverifikasi admin.</p>
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

        <form method="post" action="{{ route('kepala.anggota.update', $anggota->id) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                    <input name="nama" value="{{ old('nama', $anggota->nama) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <div class="flex space-x-2">
                        <input id="nik-input" name="nik" value="{{ old('nik', $anggota->nik) }}" class="flex-1 mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <button type="button" id="btn-recommend-nik" class="px-3 py-2 bg-gray-100 rounded text-sm">Rekomendasi</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input name="tempat_lahir" value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ optional($anggota->tanggal_lahir)->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <input name="jenis_kelamin" value="{{ old('jenis_kelamin', $anggota->jenis_kelamin) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Agama</label>
                    <input name="agama" value="{{ old('agama', $anggota->agama) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pendidikan</label>
                    <input name="pendidikan" value="{{ old('pendidikan', $anggota->pendidikan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input name="pekerjaan" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Perkawinan</label>
                    <select name="status_perkawinan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="belum kawin" {{ old('status_perkawinan', $anggota->status_perkawinan)=='belum kawin'?'selected':'' }}>Belum kawin</option>
                        <option value="kawin" {{ old('status_perkawinan', $anggota->status_perkawinan)=='kawin'?'selected':'' }}>Kawin</option>
                        <option value="cerai" {{ old('status_perkawinan', $anggota->status_perkawinan)=='cerai'?'selected':'' }}>Cerai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Dalam Keluarga</label>
                    <select name="status_dalam_keluarga" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Istri" {{ old('status_dalam_keluarga', $anggota->status_dalam_keluarga)=='Istri'?'selected':'' }}>Istri</option>
                        <option value="Anak" {{ old('status_dalam_keluarga', $anggota->status_dalam_keluarga)=='Anak'?'selected':'' }}>Anak</option>
                        <option value="Keluarga Lainnya" {{ old('status_dalam_keluarga', $anggota->status_dalam_keluarga)=='Keluarga Lainnya'?'selected':'' }}>Keluarga Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                    <input name="kewarganegaraan" value="{{ old('kewarganegaraan', $anggota->kewarganegaraan ?? 'Indonesia') }}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('alamat', $anggota->alamat) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1"><span id="alamat-count">{{ strlen(old('alamat', $anggota->alamat ?? '')) }}</span>/100</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select name="provinsi" id="provinsi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                    <select name="kota" id="kota" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelurahan</label>
                    <select name="kelurahan" id="kelurahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input name="kode_pos" id="kode_pos" value="{{ old('kode_pos', '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Ajukan Perubahan</button>
                <a href="{{ route('kepala.anggota.index') }}" class="ml-3 text-sm text-gray-600">Batal</a>
            </div>
        </form>

        <hr class="my-6">

        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-2">Laporkan Kematian</h3>
            <form method="post" action="{{ route('kepala.anggota.report_death', $anggota->id) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Meninggal</label>
                    <input type="date" name="tanggal_meninggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Laporkan Kematian</button>
                </div>
            </form>
        </div>
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

    // load locations json and populate selects (same logic as create)
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

            // restore old values
            const oldProv = `{{ old('provinsi', '') }}`;
            const oldKota = `{{ old('kota', '') }}`;
            const oldKec = `{{ old('kecamatan', '') }}`;
            const oldKel = `{{ old('kelurahan', '') }}`;
            const oldKodepos = `{{ old('kode_pos', '') }}`;

            if (oldProv) { provSelect.value = oldProv; provSelect.dispatchEvent(new Event('change')); }
            if (oldKodepos) { kodePosInput.value = oldKodepos; }
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
        const oldKota = `{{ old('kota', '') }}`;
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
        const oldKec = `{{ old('kecamatan', '') }}`;
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
        const oldKel = `{{ old('kelurahan', '') }}`;
        if (oldKel) { kelSelect.value = oldKel; kelSelect.dispatchEvent(new Event('change')); }
    });

    kelSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const kode = opt ? opt.getAttribute('data-kodepos') : '';
        kodePosInput.value = kode || '';
    });

    // NIK input sanitization and recommendation button
    const nikInputHtml = document.getElementById('nik-input');
    if (nikInputHtml) {
        nikInputHtml.setAttribute('maxlength', '16');
        nikInputHtml.addEventListener('input', function () { this.value = this.value.replace(/[^0-9]/g, '').slice(0,16); });
    }
    const btnRec = document.getElementById('btn-recommend-nik');
    if (btnRec && nikInputHtml) {
        btnRec.addEventListener('click', function () {
            btnRec.disabled = true; const orig = btnRec.textContent; btnRec.textContent = 'Mencari...';
            fetch('/kepala/nik/recommendation')
                .then(r => r.json())
                .then(data => { if (data && data.nik) nikInputHtml.value = data.nik; else alert('Gagal mendapatkan rekomendasi NIK.'); })
                .catch(() => alert('Gagal menghubungi server untuk rekomendasi NIK.'))
                .finally(() => { btnRec.disabled = false; btnRec.textContent = orig; });
        });
    }
});
</script>
@endsection
