<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\KepalaKeluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Log;

class KepalaList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $openId = null;
    public $loadedAnggota = [];

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        // Reset pagination and explicitly go to page 1 so Livewire applies the new results
        $this->resetPage();
        try {
            Log::info('KepalaList updatedSearch called', ['search' => $this->search]);
        } catch (\Throwable $e) {}
        // Some Livewire pagination states can be sticky; ensure we force the page to 1
        if (method_exists($this, 'gotoPage')) {
            $this->gotoPage(1);
        }
    }

    public function toggleOpen($id)
    {
        if ($this->openId === $id) {
            $this->openId = null;
            return;
        }

        $this->openId = $id;
        if (!isset($this->loadedAnggota[$id])) {
            $anggota = AnggotaKeluarga::where('kepala_keluarga_id', $id)->orderBy('status_dalam_keluarga')->get();
            $this->loadedAnggota[$id] = $anggota->toArray();
        }
    }

    public function render()
    {
        $query = KepalaKeluarga::query();
        if ($this->search) {
            $q = "%{$this->search}%";
            $query->where('nama', 'like', $q)->orWhere('nik', 'like', $q);
        }

        $kepalas = $query->withCount('anggota')->orderBy('created_at', 'desc')->paginate($this->perPage);
        // Log render info for debugging search/pagination interactions
        try {
            Log::info('KepalaList render', [
                'search' => $this->search,
                'total' => $kepalas->total(),
                'page' => $kepalas->currentPage(),
                'perPage' => $this->perPage,
            ]);
        } catch (\Throwable $e) {
            // avoid breaking rendering if logging fails
        }
        return view('livewire.admin.kepala-list', compact('kepalas'));
    }
}
