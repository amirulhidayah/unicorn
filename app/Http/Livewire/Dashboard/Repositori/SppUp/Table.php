<?php

namespace App\Http\Livewire\Dashboard\Repositori\SppUp;

use App\Models\SekretariatDaerah;
use App\Models\SppUp;
use App\Models\Tahun;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshTable'];

    public $totalPagination = '25';
    public $cari = '';

    public $status_verifikasi_asn;
    public $status_verifikasi_ppk;
    public $status_verifikasi_akhir;
    public $tahun;
    public $sekretariat_daerah;
    public $status_upload_skm;
    public $status_upload_arsip_sp2d;

    public $daftarTahun = null;
    public $daftarSekretariatDaerah = null;

    public function mount()
    {
        $this->daftarTahun = Tahun::orderBy('tahun', 'asc')->get();
        $this->daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();
    }

    public function getData($pagination = true)
    {
        $cari = $this->cari;
        $sekretariatDaerah = $this->sekretariat_daerah;
        $tahun = $this->tahun;
        $datas = SppUp::where(function ($query) use ($cari, $tahun, $sekretariatDaerah) {
            if (in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                if (isset($sekretariatDaerah) && $sekretariatDaerah != 'Semua') {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                }
            } else {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            $query->where('status_validasi_ppk', 1);
            $query->where('status_validasi_akhir', 1);
            $query->whereNotNull('dokumen_spm');
            $query->whereNotNull('dokumen_arsip_sp2d');

            if (isset($tahun) && $tahun != 'Semua') {
                $query->where('tahun_id', $tahun);
            }

            if ($cari) {
                $query->where('nomor_surat', 'like', "%" . $cari . "%");
                $query->orWhereHas('kegiatanSppUp', function ($query) use ($cari) {
                    $query->whereHas('kegiatan', function ($query) use ($cari) {
                        $query->where('nama', 'like', "%" . $cari . "%");
                        $query->orWhere('no_rek', 'like', "%" . $cari . "%");
                        $query->orWhereHas('program', function ($query) use ($cari) {
                            $query->where('nama', 'like', "%" .  $cari . "%");
                            $query->orWhere('no_rek', 'like', "%" . $cari . "%");
                        });
                    });
                });
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
        return view('livewire.dashboard.repositori.spp-up.table', [
            'datas' =>  $this->getData()
        ]);
    }
}
