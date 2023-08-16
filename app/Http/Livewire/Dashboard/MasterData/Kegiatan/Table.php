<?php

namespace App\Http\Livewire\Dashboard\MasterData\Kegiatan;

use App\Models\Kegiatan;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshTable'];

    public $totalPagination = '25';
    public $cari = '';
    public $idProgram = '';

    public function getData($pagination = true)
    {
        $datas = Kegiatan::where('program_id', $this->idProgram)->where('nama', 'like', '%' . $this->cari . '%')->orderBy('created_at', 'desc')->when($pagination, function ($query) {
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

    public function mount()
    {
        $this->idProgram = Request::segment(3);
    }

    public function render()
    {
        return view('livewire.dashboard.master-data.kegiatan.table', [
            'datas' =>  $this->getData()
        ]);
    }
}