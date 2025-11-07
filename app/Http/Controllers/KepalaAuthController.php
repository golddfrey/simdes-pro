<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KepalaKeluarga;

class KepalaAuthController extends Controller
{
    public function showLogin()
    {
        return view('kepala.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|string',
        ]);

        $kepala = KepalaKeluarga::where('nik', $data['nik'])->first();

        if (! $kepala) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan'])->withInput();
        }

        // store kepala id in session to mark as authenticated
        $request->session()->put('kepala_keluarga_id', $kepala->id);

        return redirect()->route('kepala.dashboard');
    }

    public function dashboard(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) {
            return redirect()->route('kepala.login');
        }

        $kepala = KepalaKeluarga::find($kepalaId);
        if (! $kepala) {
            $request->session()->forget('kepala_keluarga_id');
            return redirect()->route('kepala.login');
        }

        return view('kepala.dashboard', compact('kepala'));
    }

    // print KK view (HTML print; browser can save as PDF)
    public function printKK(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) return redirect()->route('kepala.login');

        $kepala = KepalaKeluarga::find($kepalaId);
        if (! $kepala) return redirect()->route('kepala.login');

        // build alamat similar to dashboard logic
        $alamat = $kepala->alamat ?? null;
        if (empty($alamat)) {
            $anggotaAlamat = \App\Models\AnggotaKeluarga::where('kepala_keluarga_id', $kepala->id)
                ->whereNotNull('alamat')
                ->orderByRaw("CASE WHEN status_dalam_keluarga LIKE '%Kepala%' THEN 0 ELSE 1 END")
                ->first();
            $alamat = $anggotaAlamat->alamat ?? '-';
        }

        $anggota = \App\Models\AnggotaKeluarga::where('kepala_keluarga_id', $kepala->id)->get();

        return view('kepala.kk.print', compact('kepala', 'alamat', 'anggota'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('kepala_keluarga_id');
        return redirect()->route('home');
    }
}
