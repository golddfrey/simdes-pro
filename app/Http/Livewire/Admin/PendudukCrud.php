<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendudukCrud extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 15;
    public $showForm = false;
    public $editingId = null;

    public $form = [
        'nik' => '',
        'nama' => '',
        'jenis_kelamin' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'agama' => '',
        'nomor_telepon' => '',
        'alamat' => '',
        'kecamatan' => '',
        'kelurahan' => '',
        'kota' => 'Kota Makassar',
    ];

    protected $rules = [
        'form.nik' => 'required|string|size:16|unique:penduduks,nik',
        'form.nama' => 'required|string|max:255',
        'form.jenis_kelamin' => 'nullable|in:L,P',
        'form.tanggal_lahir' => 'nullable|date',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $p = Penduduk::findOrFail($id);
        $this->form = [
            'nik' => $p->nik,
            'nama' => $p->nama,
            'jenis_kelamin' => $p->jenis_kelamin,
            'tempat_lahir' => $p->tempat_lahir,
            'tanggal_lahir' => optional($p->tanggal_lahir)->format('Y-m-d') ?? ($p->tanggal_lahir ? explode('T',$p->tanggal_lahir)[0] : ''),
            'agama' => $p->agama,
            'nomor_telepon' => $p->nomor_telepon,
            'alamat' => $p->alamat,
            'kecamatan' => $p->kecamatan,
            'kelurahan' => $p->kelurahan,
            'kota' => $p->kota,
        ];
        $this->editingId = $p->id;
        $this->showForm = true;

        // adjust unique rule for editing
        $this->rules['form.nik'] = 'required|string|size:16|unique:penduduks,nik,'.$p->id;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $p = Penduduk::findOrFail($this->editingId);
            $p->update($this->form);
            session()->flash('success', 'Penduduk diperbarui.');
        } else {
            Penduduk::create($this->form);
            session()->flash('success', 'Penduduk dibuat.');
        }

        $this->showForm = false;
        $this->resetPage();
    }

    public function delete($id)
    {
        $p = Penduduk::find($id);
        if ($p) {
            $p->delete();
            session()->flash('success', 'Penduduk dihapus.');
        }
        $this->resetPage();
    }

    protected function resetForm()
    {
        $this->form = [
            'nik' => '', 'nama' => '', 'jenis_kelamin' => '', 'tempat_lahir' => '', 'tanggal_lahir' => '', 'agama' => '', 'nomor_telepon' => '', 'alamat' => '', 'kecamatan' => '', 'kelurahan' => '', 'kota' => 'Kota Makassar'
        ];
        $this->rules['form.nik'] = 'required|string|size:16|unique:penduduks,nik';
    }

    public function render()
    {
        $start = microtime(true);
        // enable query log for diagnosis (temporary)
        DB::flushQueryLog();
        DB::enableQueryLog();

        $q = $this->search;
        $query = Penduduk::query();
        if ($q) {
            $like = "%{$q}%";
            $query->where('nama', 'like', $like)->orWhere('nik', 'like', $like)->orWhere('alamat', 'like', $like);
        }
        // Use simplePaginate to avoid expensive COUNT(*) on large tables.
        // simplePaginate performs a LIMIT+1 fetch and does not calculate total rows.
        // Select only the columns needed for the table to reduce IO
        $penduduks = $query->select(['id','nik','nama','jenis_kelamin','alamat'])
            ->orderBy('nama')
            ->simplePaginate($this->perPage);

        $queries = DB::getQueryLog();
        $duration = microtime(true) - $start;
        Log::info("[PendudukCrud] render: duration={duration}s, queries={count}", [
            'duration' => round($duration, 3),
            'count' => count($queries),
        ]);

        // log sample queries for inspection
        if (count($queries) > 0) {
            $sample = array_slice($queries, 0, 10);
            Log::debug('[PendudukCrud] sample queries', $sample);
        }

        return view('livewire.admin.penduduk-crud', compact('penduduks'));
    }
}
