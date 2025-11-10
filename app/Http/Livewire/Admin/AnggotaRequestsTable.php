<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AnggotaKeluargaChangeRequest;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class AnggotaRequestsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $perPage = 15;
    public $showDetail = false;
    public $selectedId = null;
    public $rejectReason = '';

    protected $listeners = ['refreshRequests' => '$refresh'];

    public function render()
    {
        $requests = AnggotaKeluargaChangeRequest::where('status', 'pending')
            ->with('kepala')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.anggota-requests-table', compact('requests'));
    }

    // rename method to avoid collision with public $showDetail property
    public function openDetail($id)
    {
        $this->selectedId = $id;
        $this->showDetail = true;
        $this->rejectReason = '';
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedId = null;
        $this->rejectReason = '';
    }

    public function approve($id)
    {
        $req = AnggotaKeluargaChangeRequest::find($id);
        if (!$req) {
            session()->flash('error', 'Permintaan tidak ditemukan.');
            return;
        }

        if ($req->action === 'add') {
            AnggotaKeluarga::create(array_merge($req->payload ?? [], ['kepala_keluarga_id' => $req->kepala_keluarga_id]));
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

        session()->flash('success', 'Permintaan berhasil disetujui.');
        // Livewire v3: emit() was replaced by dispatch()
        $this->dispatch('refreshRequests');
        $this->closeDetail();
    }

    public function reject($id)
    {
        $req = AnggotaKeluargaChangeRequest::find($id);
        if (!$req) {
            session()->flash('error', 'Permintaan tidak ditemukan.');
            return;
        }

        $req->update(['status' => 'rejected', 'reason' => $this->rejectReason ?: null, 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);

        session()->flash('success', 'Permintaan ditolak.');
        // Livewire v3: emit() was replaced by dispatch()
        $this->dispatch('refreshRequests');
        $this->closeDetail();
    }
}
