<?php

namespace App\Http\Livewire\Dashboard\Spp\SppUp;

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
        $statusVerifikasiAsn = $this->status_verifikasi_asn;
        $statusVerifikasiPpk = $this->status_verifikasi_ppk;
        $statusVerifikasiAkhir = $this->status_verifikasi_akhir;
        $statusUploadSkm = $this->status_upload_skm;
        $statusUploadArsipSp2d = $this->status_upload_arsip_sp2d;
        $sekretariatDaerah = $this->sekretariat_daerah;
        $tahun = $this->tahun;
        $datas = SppUp::where(function ($query) use ($cari, $statusVerifikasiAsn, $statusVerifikasiPpk, $statusVerifikasiAkhir, $statusUploadSkm, $statusUploadArsipSp2d, $tahun, $sekretariatDaerah) {
            if (in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'])) {
                if (isset($sekretariatDaerah) && $sekretariatDaerah != 'Semua') {
                    $query->where('sekretariat_daerah_id', $sekretariatDaerah);
                }
            } else if (Auth::user()->role == 'Operator SPM') {
                $query->where('status_validasi_asn', 1);
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
            } else {
                $query->where('sekretariat_daerah_id', Auth::user()->profil->sekretariat_daerah_id);
            }

            if (isset($statusVerifikasiAsn) && $statusVerifikasiAsn != 'Semua') {
                if ($statusVerifikasiAsn == "Belum Diproses") {
                    $query->where('status_validasi_asn', 0);
                } else if ($statusVerifikasiAsn == "Ditolak") {
                    $query->where('status_validasi_asn', 2);
                } else {
                    $query->where('status_validasi_asn', 1);
                }
            }

            if (isset($statusVerifikasiPpk) && $statusVerifikasiPpk != 'Semua') {
                if ($statusVerifikasiPpk == "Belum Diproses") {
                    $query->where('status_validasi_ppk', 0);
                } else if ($statusVerifikasiPpk == "Ditolak") {
                    $query->where('status_validasi_ppk', 2);
                } else {
                    $query->where('status_validasi_ppk', 1);
                }
            }

            if (isset($statusVerifikasiAkhir) && $statusVerifikasiAkhir != 'Semua') {
                if ($statusVerifikasiAkhir == "Belum Diproses") {
                    $query->where('status_validasi_akhir', 0);
                } else {
                    $query->where('status_validasi_akhir', 1);
                }
            }

            if (isset($statusUploadSkm) && $statusUploadSkm != 'Semua') {
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                if ($statusUploadSkm == "Belum Ada") {
                    $query->whereNull('dokumen_spm');
                } else {
                    $query->whereNotNull('dokumen_spm');
                }
            }

            if (isset($statusUploadArsipSp2d) && $statusUploadArsipSp2d != 'Semua') {
                $query->where('status_validasi_ppk', 1);
                $query->where('status_validasi_akhir', 1);
                $query->whereNotNull('dokumen_spm');
                if ($statusUploadArsipSp2d == "Belum Ada") {
                    $query->whereNull('dokumen_arsip_sp2d');
                } else {
                    $query->whereNotNull('dokumen_arsip_sp2d');
                }
            }

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
        return view('livewire.dashboard.spp.spp-up.table', [
            'datas' =>  $this->getData()
        ]);
    }
}
