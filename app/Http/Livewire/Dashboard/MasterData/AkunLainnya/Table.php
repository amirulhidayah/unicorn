<?php

namespace App\Http\Livewire\Dashboard\MasterData\AkunLainnya;

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
    public $aktif;

    public function getData($pagination = true)
    {
        $cari = $this->cari;
        $role = $this->role;
        $aktif = $this->aktif;

        $datas = User::orderBy('created_at', 'desc')->whereNotIn('role', ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])->orderBy('created_at', 'desc')->where(function ($query) use ($cari, $role, $aktif) {
            if ($cari) {
                $query->whereHas('profil', function ($query) use ($cari) {
                    $query->where('nama', 'like', '%' . $this->cari . '%');
                });
            }

            if (isset($role) && $role != 'Semua') {
                $query->where('role', $role);
            }

            if (isset($aktif) && $aktif != 'Semua') {
                $query->where('is_aktif', $aktif);
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
        return view('livewire.dashboard.master-data.akun-lainnya.table', [
            'datas' =>  $this->getData()
        ]);
    }
}
