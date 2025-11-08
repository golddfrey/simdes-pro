<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Penduduk;
use Illuminate\Support\Carbon;

class PendudukCrud extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    // number of items per page (user-selectable)
    public $perPage = 10;
    // available per-page options
    public $perPageOptions = [10, 50, 100];
    // sorting
    public $sortBy = 'nama'; // default sort column
    public $sortDir = 'asc'; // asc or desc
    // age filters (in years)
    public $ageMin = null;
    public $ageMax = null;
    public $showForm = false;
    public $editingId = null;
    // detail modal
    public $showDetail = false;
    public $detailPenduduk = null;

    // select options
    public $agamaOptions = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'];
    public $statusOptions = ['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'];
    public $pekerjaanOptions = ['Pegawai Negeri','Karyawan Swasta','Wirausaha','Pensiunan','Tidak Ada'];

    public $form = [
        'nik' => '',
        'nama' => '',
        'jenis_kelamin' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'agama' => '',
        'status_perkawinan' => '',
        'pekerjaan' => '',
        'nomor_telepon' => '',
        'alamat' => '',
        'kecamatan' => '',
        'kelurahan' => '',
        'kota' => 'Kota Makassar',
    ];
    // confirmation modal before saving
    public $confirmingSave = false;
    // immediate saved message for UI
    public $savedMessage = null;

    protected $rules = [
        'form.nik' => 'required|string|size:16|unique:penduduks,nik',
        'form.nama' => 'required|string|max:255',
        'form.jenis_kelamin' => 'nullable|in:L,P',
        'form.tanggal_lahir' => 'nullable|date',
        'form.status_perkawinan' => 'nullable|string',
        'form.pekerjaan' => 'nullable|string',
    ];

    // Persist common state in query string so pagination and filters play nicely
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'ageMin' => ['except' => ''],
        'ageMax' => ['except' => ''],
        'sortBy' => ['except' => 'nama'],
        'sortDir' => ['except' => 'asc'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // When perPage changes, go back to page 1
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // reset filters helper
    public function resetFilters()
    {
        $this->ageMin = null;
        $this->ageMax = null;
        $this->search = '';
        $this->resetPage();
    }

    // When filters/sort change, reset pagination to first page
    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedSortDir()
    {
        $this->resetPage();
    }

    public function updatedAgeMin()
    {
        $this->resetPage();
    }

    public function updatedAgeMax()
    {
        $this->resetPage();
    }

    // Toggle sorting: if same column, flip direction; otherwise set new column asc
    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
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
            'status_perkawinan' => $p->status_perkawinan ?? '',
            'pekerjaan' => $p->pekerjaan ?? '',
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
        // When editing, ensure the unique rule for NIK ignores the current record id
        if ($this->editingId) {
            $this->rules['form.nik'] = 'required|string|size:16|unique:penduduks,nik,'.$this->editingId.',id';
        } else {
            $this->rules['form.nik'] = 'required|string|size:16|unique:penduduks,nik';
        }

        $this->validate();

        // Only save columns that actually exist in the penduduks table to avoid SQL errors
        $data = [];
        foreach ($this->form as $k => $v) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('penduduks', $k)) {
                $data[$k] = $v;
            }
        }

        if ($this->editingId) {
            $p = Penduduk::findOrFail($this->editingId);
            $p->update($data);
            session()->flash('success', 'Penduduk diperbarui.');
            $this->savedMessage = 'Penduduk diperbarui.';
        } else {
            Penduduk::create($data);
            session()->flash('success', 'Penduduk dibuat.');
            $this->savedMessage = 'Penduduk dibuat.';
        }

        $this->showForm = false;
        $this->resetPage();
    }

    public function confirmSave()
    {
        $this->confirmingSave = true;
    }

    public function confirmAndSave()
    {
        // call existing save flow
        $this->save();
        $this->confirmingSave = false;
    }

    public function showDetail($id)
    {
        $p = Penduduk::find($id);
        if ($p) {
            $this->detailPenduduk = $p->toArray();
            $this->showDetail = true;
        }
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
            'nik' => '', 'nama' => '', 'jenis_kelamin' => '', 'tempat_lahir' => '', 'tanggal_lahir' => '', 'agama' => '', 'status_perkawinan' => '', 'pekerjaan' => '', 'nomor_telepon' => '', 'alamat' => '', 'kecamatan' => '', 'kelurahan' => '', 'kota' => 'Kota Makassar'
        ];
        $this->rules['form.nik'] = 'required|string|size:16|unique:penduduks,nik';
    }

    public function render()
    {
    // render without diagnostic query logging in normal operation

        $q = $this->search;
        $query = Penduduk::query();

        // --- Search ---
        if ($q) {
            $like = "%{$q}%";
            $query->where(function ($sub) use ($like) {
                $sub->where('nama', 'like', $like)
                    ->orWhere('nik', 'like', $like)
                    ->orWhere('alamat', 'like', $like);
            });
        }

        // --- Age filtering (convert ages to tanggal_lahir range) ---
        // normalize if user accidentally entered min > max
        if ($this->ageMin !== null && $this->ageMax !== null && $this->ageMin !== '' && $this->ageMax !== '' && (int)$this->ageMin > (int)$this->ageMax) {
            // swap to keep logical order
            $tmp = $this->ageMin;
            $this->ageMin = $this->ageMax;
            $this->ageMax = $tmp;
        }
        // If user sets min/max age, we compute birthdate bounds. Use whereBetween when both present.
        $today = Carbon::now();
        $hasMin = $this->ageMin !== null && $this->ageMin !== '';
        $hasMax = $this->ageMax !== null && $this->ageMax !== '';
        if ($hasMin && $hasMax) {
            $upper = $today->copy()->subYears((int) $this->ageMin)->endOfDay()->toDateString();
            $lower = $today->copy()->subYears((int) $this->ageMax)->startOfDay()->toDateString();
            $query->whereBetween('tanggal_lahir', [$lower, $upper]);
        } elseif ($hasMin) {
            $upper = $today->copy()->subYears((int) $this->ageMin)->endOfDay()->toDateString();
            $query->where('tanggal_lahir', '<=', $upper);
        } elseif ($hasMax) {
            $lower = $today->copy()->subYears((int) $this->ageMax)->startOfDay()->toDateString();
            $query->where('tanggal_lahir', '>=', $lower);
        }

        // --- Sorting ---
        // Support sorting by 'nama', 'nik' and 'umur' (umur uses tanggal_lahir direction reversed)
        if ($this->sortBy === 'umur') {
            // when sorting by umur asc, oldest first -> tanggal_lahir asc (earlier date = older)
            $dir = $this->sortDir === 'asc' ? 'asc' : 'desc';
            $query->orderBy('tanggal_lahir', $dir);
        } else {
            $column = in_array($this->sortBy, ['nama', 'nik']) ? $this->sortBy : 'nama';
            $query->orderBy($column, $this->sortDir);
        }

        // Select only the columns needed for the table to reduce IO
        $perPage = (int) $this->perPage ?: 10;
        $selectCols = ['id','nik','nama','jenis_kelamin','alamat','tanggal_lahir'];

        // If the user is actively searching or filtering by age, use simplePaginate()
        // to avoid a heavy COUNT(*) (faster for interactive searches). When no
        // search/filters are active, return full paginate() so the UI can show
        // numbered pages.
        $isFiltering = ($q && trim($q) !== '') || $hasMin || $hasMax;
        if ($isFiltering) {
            $penduduks = $query->select($selectCols)
                ->simplePaginate($perPage)->withQueryString();
        } else {
            $penduduks = $query->select($selectCols)
                ->paginate($perPage)->withQueryString();
        }

        // normal render path — no verbose logging

        return view('livewire.admin.penduduk-crud', compact('penduduks'));
    }
}
