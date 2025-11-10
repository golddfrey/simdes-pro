<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnggotaKeluarga;
use App\Models\AnggotaKeluargaChangeRequest;
use App\Models\KepalaKeluarga;
use App\Models\User;
use App\Notifications\NewAnggotaChangeRequest;

class AnggotaKeluargaController extends Controller
{
    // List anggota for a kepala keluarga
    public function index(Request $request)
    {
        // assuming kepala_keluarga_id stored in session after login
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        $anggota = AnggotaKeluarga::where('kepala_keluarga_id', $kepalaId)->get();
        return view('kepala.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('kepala.anggota.create');
    }

    // When kepala adds anggota, we create a change request for admin review
    public function store(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        // Server-side validation rules per requirement
        // Assumption: NIK must be 16 digits (standard KTP). If you require 17, tell me and I'll change it.
        $rules = [
            'nama' => 'required|string|max:255',
            'nik' => ['nullable','digits:16'],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama' => 'required|string|in:Islam,Kristen,Katholik,Hindu,Buddha,Konghucu,Lainnya',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|string|in:belum kawin,kawin,cerai',
            'status_dalam_keluarga' => 'required|in:Istri,Anak,Keluarga Lainnya',
            'kewarganegaraan' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:100',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'kode_pos' => 'required|string',
        ];

        $data = $request->validate($rules);

        // enforce default kewarganegaraan = Indonesia
        if (empty($data['kewarganegaraan'])) {
            $data['kewarganegaraan'] = 'Indonesia';
        }

        // Cross-check province/city/kecamatan/kelurahan/kode_pos consistency using JSON dataset
        $locationsPath = base_path('public/js/indonesia-locations.json');
        if (file_exists($locationsPath)) {
            $locations = json_decode(file_get_contents($locationsPath), true);
            $valid = false;

            foreach ($locations as $prov) {
                if ($prov['name'] === $data['provinsi']) {
                    foreach ($prov['cities'] as $city) {
                        if ($city['name'] === $data['kota']) {
                            foreach ($city['kecamatan'] as $kec) {
                                if ($kec['name'] === $data['kecamatan']) {
                                    foreach ($kec['kelurahan'] as $kel) {
                                        if ($kel['name'] === $data['kelurahan'] && ($kel['kode_pos'] === $data['kode_pos'])) {
                                            $valid = true;
                                            break 4;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (! $valid) {
                return back()->withInput()->withErrors(['provinsi' => 'Lokasi (provinsi/kota/kecamatan/kelurahan/kode pos) tidak konsisten atau tidak ditemukan.']);
            }
        } else {
            // if dataset missing, continue but warn in session
            session()->flash('status', 'Perhatian: dataset lokasi tidak ditemukan di server. Validasi lokasi dilewati.');
        }

        $cr = AnggotaKeluargaChangeRequest::create([
            'kepala_keluarga_id' => $kepalaId,
            'action' => 'add',
            'payload' => $data,
            'status' => 'pending',
        ]);

        // Notify all admin users via database notifications
        try {
            $admins = User::where('is_admin', true)->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new NewAnggotaChangeRequest($cr));
            }
        } catch (\Throwable $e) {
            // swallow notification errors but log for visibility
            \Log::error('Failed to send admin notification for change request: '.$e->getMessage());
        }

        return redirect()->route('kepala.anggota.index')->with('status', 'Pengajuan penambahan anggota keluarga dibuat, menunggu persetujuan.');
    }

    public function edit(Request $request, $id)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        $anggota = AnggotaKeluarga::where('kepala_keluarga_id', $kepalaId)->findOrFail($id);
        return view('kepala.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        $anggota = AnggotaKeluarga::where('kepala_keluarga_id', $kepalaId)->findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => ['nullable','digits:16'],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'agama' => 'required|string|in:Islam,Kristen,Katholik,Hindu,Buddha,Konghucu,Lainnya',
            'pendidikan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'nullable|string|in:belum kawin,kawin,cerai',
            'status_dalam_keluarga' => 'required|in:Istri,Anak,Keluarga Lainnya',
            'kewarganegaraan' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:100',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'kode_pos' => 'required|string',
        ]);

        // create update change request
            $cr = AnggotaKeluargaChangeRequest::create([
            'kepala_keluarga_id' => $kepalaId,
            'anggota_keluarga_id' => $anggota->id,
            'action' => 'update',
            'payload' => $data,
            'status' => 'pending',
        ]);

            // notify admins
            try {
                $admins = User::where('is_admin', true)->get();
                if ($admins->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($admins, new NewAnggotaChangeRequest($cr));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send admin notification for change request (update): '.$e->getMessage());
            }

        return redirect()->route('kepala.dashboard')->with('status', 'Pengajuan perubahan data anggota keluarga dibuat, menunggu persetujuan.');
    }

    // report death: create change request with action = death
    public function reportDeath(Request $request, $id)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        $anggota = AnggotaKeluarga::where('kepala_keluarga_id', $kepalaId)->findOrFail($id);

        $data = $request->validate([
            'tanggal_meninggal' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

            $cr = AnggotaKeluargaChangeRequest::create([
            'kepala_keluarga_id' => $kepalaId,
            'anggota_keluarga_id' => $anggota->id,
            'action' => 'death',
            'payload' => $data,
            'status' => 'pending',
        ]);

            // notify admins about death report
            try {
                $admins = User::where('is_admin', true)->get();
                if ($admins->isNotEmpty()) {
                    \Illuminate\Support\Facades\Notification::send($admins, new NewAnggotaChangeRequest($cr));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send admin notification for death report: '.$e->getMessage());
            }

            return redirect()->route('kepala.dashboard')->with('status', 'Laporan kematian dibuat, menunggu verifikasi admin.');
    }
}
