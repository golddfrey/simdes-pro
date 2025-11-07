<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Penduduk;
use App\Models\KepalaKeluarga;

class KepalaCreate extends Component
{
    public $query = '';
    public $suggestions = [];
    public $selectedNik = '';
    public $highlight = 0;
    public $showModal = false;

    public $form = [
        'nik' => '',
        'nama' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'agama' => '',
        'jenis_kelamin' => '',
        'nomor_telepon' => '',
    ];

    protected function rules()
    {
        return [
            'form.nik' => 'required|string|exists:penduduks,nik|unique:kepala_keluargas,nik',
            'form.nama' => 'required|string|max:255',
            'form.tempat_lahir' => 'nullable|string|max:255',
            'form.tanggal_lahir' => 'nullable|date',
            'form.agama' => 'nullable|string|max:50',
            'form.jenis_kelamin' => 'nullable|in:L,P',
            'form.nomor_telepon' => 'nullable|string|max:20',
        ];
    }

    public function updatedQuery()
    {
        $q = trim($this->query);
        // if user has previously selected a NIK but now typing something else,
        // clear the selection so fields become editable again
        if ($this->selectedNik && $q !== $this->selectedNik) {
            $this->selectedNik = '';
        }

        if ($q === '') {
            $this->suggestions = [];
            $this->highlight = 0;
            return;
        }

        $this->suggestions = Penduduk::where('nik', 'like', "%{$q}%")
            ->orWhere('nama', 'like', "%{$q}%")
            ->limit(20)
            ->get(['nik','nama','tempat_lahir','tanggal_lahir','agama','jenis_kelamin','nomor_telepon'])
            ->toArray();

        // reset highlight when new suggestions loaded
        $this->highlight = 0;
    }

    public function selectSuggestion($nik)
    {
        $p = Penduduk::where('nik', $nik)->first();
        if (!$p) return;

        $this->form['nik'] = $p->nik;
        $this->form['nama'] = $p->nama;
        $this->form['tempat_lahir'] = $p->tempat_lahir ?? '';
        $this->form['tanggal_lahir'] = optional($p->tanggal_lahir)->format('Y-m-d') ?? ($p->tanggal_lahir ? explode('T', $p->tanggal_lahir)[0] : '');
        $this->form['agama'] = $p->agama ?? '';
        $this->form['jenis_kelamin'] = $p->jenis_kelamin ?? '';
        $this->form['nomor_telepon'] = $p->nomor_telepon ?? '';

        $this->selectedNik = $p->nik;
        $this->query = $p->nik;
        $this->suggestions = [];
        $this->highlight = 0;
    }

    public function highlightNext()
    {
        if (count($this->suggestions) === 0) return;
        $this->highlight = min($this->highlight + 1, count($this->suggestions) - 1);
    }

    public function highlightPrev()
    {
        if (count($this->suggestions) === 0) return;
        $this->highlight = max($this->highlight - 1, 0);
    }

    public function selectHighlighted()
    {
        if (!isset($this->suggestions[$this->highlight])) return;
        $nik = $this->suggestions[$this->highlight]['nik'];
        $this->selectSuggestion($nik);
    }

    public function confirmSave()
    {
        $this->validate();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        KepalaKeluarga::create([
            'nik' => $this->form['nik'],
            'nama' => $this->form['nama'],
            'tempat_lahir' => $this->form['tempat_lahir'] ?? null,
            'tanggal_lahir' => $this->form['tanggal_lahir'] ?? null,
            'agama' => $this->form['agama'] ?? null,
            'jenis_kelamin' => $this->form['jenis_kelamin'] ?? null,
            'nomor_telepon' => $this->form['nomor_telepon'] ?? null,
        ]);

        session()->flash('success', 'Kepala keluarga berhasil ditambahkan.');
        return redirect()->route('admin.kepala.index');
    }

    public function render()
    {
        return view('livewire.admin.kepala-create');
    }
}
