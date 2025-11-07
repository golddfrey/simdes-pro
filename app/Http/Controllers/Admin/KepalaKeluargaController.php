<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KepalaKeluarga;

class KepalaKeluargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // show 10 kepala per page as requested
        $kepalas = KepalaKeluarga::withCount('anggota')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kepala.index', compact('kepalas'));
    }

    public function anggota($id)
    {
        $kepala = KepalaKeluarga::findOrFail($id);
        $anggota = $kepala->anggota()->orderBy('status_dalam_keluarga')->get();
        return view('admin.kepala._anggota_list', compact('anggota'));
    }

    public function create()
    {
        return view('admin.kepala.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // require nik to exist in penduduks (source of truth) and be unique among kepala_keluargas
            'nik' => 'required|string|exists:penduduks,nik|unique:kepala_keluargas,nik',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        KepalaKeluarga::create($data);

        return redirect()->route('admin.kepala.index')->with('success', 'Kepala keluarga berhasil ditambahkan.');
    }
}
