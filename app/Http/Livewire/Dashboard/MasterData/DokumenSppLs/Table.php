<?php

namespace App\Http\Livewire\Dashboard\MasterData\DokumenSppLs;

use App\Models\DaftarDokumenSppLs;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshTable'];

    public $totalPagination = '25';
    public $cari = '';
    public $kategori_filter;

    public function getData($pagination = true)
    {
        $kategoriFilter = $this->kategori_filter;
        $cari = $this->cari;
        $datas = DaftarDokumenSppLs::where(function ($query) use ($cari, $kategoriFilter) {
            $query->where('nama', 'like', '%' . $cari . '%');
            if (isset($kategoriFilter) && $kategoriFilter != 'Semua') {
                $query->where('kategori', $kategoriFilter);
            }
        })->orderBy('created_at', 'desc')->when($pagination, function ($query) {
            return $query->paginate($this->totalPagination);
        }, function ($query) {
            return $query->get();
        });
        return $datas;
    }

    public function updatedTotalPagination()
    {
        $this->resetPage();
    }

    public function updatingCari()
    {
        $this->resetPage();
    }

    public function refreshTable()
    {
    }

    public function render()
    {
        return view('livewire.dashboard.master-data.dokumen-spp-ls.table', [
            'datas' =>  $this->getData()
        ]);
    }
}
