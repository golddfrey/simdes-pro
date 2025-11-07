<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnggotaKeluargaChangeRequest;
use App\Models\AnggotaKeluarga;

class AnggotaChangeRequestController extends Controller
{
    public function index()
    {
        // eager load kepala to avoid N+1 when rendering table
        $requests = AnggotaKeluargaChangeRequest::where('status', 'pending')->with('kepala')->latest()->get();
        return view('admin.anggota.requests.index', compact('requests'));
    }

    public function show($id)
    {
        $req = AnggotaKeluargaChangeRequest::findOrFail($id);
        return view('admin.anggota.requests.show', compact('req'));
    }

    public function approve(Request $request, $id)
    {
        $req = AnggotaKeluargaChangeRequest::findOrFail($id);

        if ($req->action === 'add') {
            $anggota = AnggotaKeluarga::create(array_merge($req->payload ?? [], ['kepala_keluarga_id' => $req->kepala_keluarga_id]));
            $req->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        } elseif ($req->action === 'update' && $req->anggota_keluarga_id) {
            $anggota = AnggotaKeluarga::find($req->anggota_keluarga_id);
            if ($anggota) {
                $anggota->update($req->payload ?? []);
                $req->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
            }
        } elseif ($req->action === 'death' && $req->anggota_keluarga_id) {
            $anggota = AnggotaKeluarga::find($req->anggota_keluarga_id);
            if ($anggota) {
                $anggota->update(['is_deceased' => true]);
                $req->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
            }
        } elseif ($req->action === 'delete' && $req->anggota_keluarga_id) {
            $anggota = AnggotaKeluarga::find($req->anggota_keluarga_id);
            if ($anggota) {
                $anggota->delete();
                $req->update(['status' => 'approved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
            }
        }

        return redirect()->route('admin.anggota.requests.index')->with('status', 'Request disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $req = AnggotaKeluargaChangeRequest::findOrFail($id);
        $data = $request->validate(['reason' => 'nullable|string']);
    $req->update(['status' => 'rejected', 'reason' => $data['reason'] ?? null, 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        return redirect()->route('admin.anggota.requests.index')->with('status', 'Request ditolak.');
    }
}
