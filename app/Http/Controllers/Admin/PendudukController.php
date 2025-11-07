<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KepalaKeluarga;
use App\Models\AnggotaKeluarga;
use App\Models\Penduduk;
use Illuminate\Support\Str;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $kepalas = KepalaKeluarga::orderBy('nama')->paginate(20, ['*'], 'kepala_page');
        $anggota = AnggotaKeluarga::orderBy('nama')->paginate(20, ['*'], 'anggota_page');

        return view('admin.penduduk.index', compact('kepalas', 'anggota'));
    }

    /**
     * Search penduduk by NIK or name for autocomplete suggestions.
     * Returns JSON array of matching penduduk (limited to 20).
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $q = Str::of($q)->trim();

        if ($q->isEmpty()) {
            return response()->json([]);
        }

        $results = Penduduk::where('nik', 'like', "%{$q}%")
            ->orWhere('nama', 'like', "%{$q}%")
            ->limit(20)
            ->get(['id','nik','nama','tempat_lahir','tanggal_lahir','agama','jenis_kelamin','nomor_telepon','alamat']);

        return response()->json($results);
    }
}
