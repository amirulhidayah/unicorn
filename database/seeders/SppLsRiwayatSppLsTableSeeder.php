<?php

namespace Database\Seeders;

use App\Models\KegiatanDpa;
use App\Models\RiwayatSppLs;
use App\Models\SekretariatDaerah;
use App\Models\Spd;
use App\Models\SppLs;
use Illuminate\Database\Seeder;

class SppLsRiwayatSppLsTableSeeder extends Seeder
{
    public function run()
    {
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April'
        ];

        $tahun = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';

        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        foreach ($daftarSekretariatDaerah as $sekretariatDaerah) {
            $kegiatanDpa = KegiatanDpa::whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                $query->where('tahun_id', $tahun);
                $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
            })->get();

            foreach ($kegiatanDpa as $kegiatan) {
                $spd = Spd::where('sekretariat_daerah_id', $sekretariatDaerah->id)->where('tahun_id', $tahun)->where('kegiatan_dpa_id', $kegiatan->id)->first();
                if ($spd) {
                    $jumlahAnggaran = $spd->jumlah_anggaran;
                    $anggaranDigunakan = 0; // Initialize the array
                    for ($i = 0; $i < count($bulan); $i++) {
                        if ($i != count($bulan) - 1) {
                            $randomNumber = ($jumlahAnggaran > 0) ? mt_rand(0, $jumlahAnggaran) : 0;
                            $anggaranDigunakan = $randomNumber;
                            $jumlahAnggaran -= $randomNumber;
                        } else {
                            $anggaranDigunakan = $jumlahAnggaran;
                        }

                        $sppLs = new SppLs();
                        $sppLs->alasan_validasi_asn = NULL;
                        $sppLs->alasan_validasi_ppk = NULL;
                        $sppLs->anggaran_digunakan = $anggaranDigunakan;
                        $sppLs->bulan = $bulan[$i];
                        $sppLs->created_at = '2023-' . ($i + 1) . '-24 06:02:55';
                        $sppLs->updated_at = '2023-' . ($i + 1) . '-24 06:09:03';
                        $sppLs->dokumen_arsip_sp2d = '1690352111.pdf';
                        $sppLs->dokumen_spm = '1690352105.pdf';
                        $sppLs->kategori = 'Belanja Hibah';
                        $sppLs->kegiatan_dpa_id = $kegiatan->id;
                        $sppLs->nomor_surat = 'SPP-LS';
                        $sppLs->sekretariat_daerah_id = $sekretariatDaerah->id;
                        $sppLs->status_validasi_akhir = 1;
                        $sppLs->status_validasi_asn = 1;
                        $sppLs->status_validasi_ppk = 1;
                        $sppLs->surat_penolakan = NULL;
                        $sppLs->tahap_riwayat = 1;
                        $sppLs->tahun_id = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';
                        $sppLs->tanggal_validasi_akhir = '2023-07-26';
                        $sppLs->tanggal_validasi_asn = '2023-07-26';
                        $sppLs->tanggal_validasi_ppk = '2023-07-26';
                        $sppLs->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                        $sppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = $randomNumber;
                        $riwayatSppLs->created_at = '2023-07-26 06:14:47';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = 'PPK';
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Disetujui';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:14:47';
                        $riwayatSppLs->user_id = '049e4da4-eb05-4216-a422-277703af887e';
                        $riwayatSppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = NULL;
                        $riwayatSppLs->created_at = '2023-07-26 06:15:11';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = 'Admin';
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Upload Arsip SP2D';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:15:11';
                        $riwayatSppLs->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                        $riwayatSppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = NULL;
                        $riwayatSppLs->created_at = '2023-07-26 06:15:05';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = 'Admin';
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Upload SPM';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:15:05';
                        $riwayatSppLs->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                        $riwayatSppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = $anggaranDigunakan;
                        $riwayatSppLs->created_at = '2023-07-26 06:14:53';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = NULL;
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Diselesaikan';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:14:53';
                        $riwayatSppLs->user_id = '049e4da4-eb05-4216-a422-277703af887e';
                        $riwayatSppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = $anggaranDigunakan;
                        $riwayatSppLs->created_at = '2023-07-26 06:14:33';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = 'ASN Sub Bagian Keuangan';
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Disetujui';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:14:33';
                        $riwayatSppLs->user_id = '81a75b22-5e0a-4a57-ac63-e2c28dec2612';
                        $riwayatSppLs->save();

                        $riwayatSppLs = new RiwayatSppLs();
                        $riwayatSppLs->alasan = NULL;
                        $riwayatSppLs->anggaran_digunakan = $anggaranDigunakan;
                        $riwayatSppLs->created_at = '2023-07-26 06:12:34';
                        $riwayatSppLs->nomor_surat = NULL;
                        $riwayatSppLs->role = NULL;
                        $riwayatSppLs->spp_ls_id = $sppLs->id;
                        $riwayatSppLs->status = 'Dibuat';
                        $riwayatSppLs->surat_penolakan = NULL;
                        $riwayatSppLs->tahap_riwayat = 1;
                        $riwayatSppLs->updated_at = '2023-07-26 06:12:34';
                        $riwayatSppLs->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                        $riwayatSppLs->save();
                    }
                }
            }
        }
    }
}
