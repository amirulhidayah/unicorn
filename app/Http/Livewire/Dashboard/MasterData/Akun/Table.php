<?php

namespace App\Http\Livewire\Dashboard\MasterData\Akun;

use App\Models\SekretariatDaerah;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshTable'];

    public $totalPagination = '25';
    public $cari = '';

    public $role;
    public $sekretariat_daerah;

    public function getData($pagination = true)
    {
        $cari = $this->cari;
        $role = $this->role;
        $sekretariatDaerah = $this->sekretariat_daerah;

        $datas = User::orderBy('created_at', 'desc')->whereIn('role', ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])->orderBy('created_at', 'desc')->where(function ($query) use ($cari, $sekretariatDaerah, $role) {
            if ($cari) {
                $query->whereHas('profil', function ($query) use ($cari) {
                    $query->where('nama', 'like', '%' . $cari . '%');
                });
            }

            if (isset($sekretariatDaerah) && $sekretariatDaerah != 'Semua') {
                $query->whereHas('profil', function ($query) use ($sekretariatDaerah) {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                });
            }

            if (isset($role) && $role != 'Semua') {
                $query->where('role', $role);
            }
        })->when($pagination, function ($query) {
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
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('created_at', 'asc')->get();
        return view('livewire.dashboard.master-data.akun.table', [
            'datas' =>  $this->getData(),
            'daftarSekretariatDaerah' => $daftarSekretariatDaerah
        ]);
    }
}
