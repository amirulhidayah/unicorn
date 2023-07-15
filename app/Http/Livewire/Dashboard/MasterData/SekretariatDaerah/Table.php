<?php

namespace App\Http\Livewire\Dashboard\MasterData\SekretariatDaerah;

use App\Models\SekretariatDaerah;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshTable'];

    public $totalPagination = '25';
    public $cari = '';

    public function getData($pagination = true)
    {
        $datas = SekretariatDaerah::where('nama', 'like', '%' . $this->cari . '%')->orderBy('id', 'desc')->when($pagination, function ($query) {
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
        return view('livewire.dashboard.master-data.sekretariat-daerah.table', [
            'datas' =>  $this->getData()
        ]);
    }
}
