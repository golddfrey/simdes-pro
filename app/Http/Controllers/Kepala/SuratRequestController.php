<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratRequest;

class SuratRequestController extends Controller
{
    public function create()
    {
        return view('kepala.surat.create');
    }

    public function store(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');

        $data = $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        SuratRequest::create(array_merge($data, ['kepala_keluarga_id' => $kepalaId, 'status' => 'pending']));

        return redirect()->route('kepala.dashboard')->with('status', 'Pengajuan surat dibuat, menunggu verifikasi.');
    }
}
