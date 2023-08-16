<?php

namespace Database\Seeders;

use App\Models\KegiatanDpa;
use App\Models\RiwayatSppGu;
use App\Models\SekretariatDaerah;
use App\Models\SppGu;
use Illuminate\Database\Seeder;

class SppGuRiwayatSppGuTableSeeder extends Seeder
{
    public function run()
    {
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tahun = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';

        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('nama', 'asc')->get();

        foreach ($daftarSekretariatDaerah as $sekretariatDaerah) {
            $kegiatanDpa = KegiatanDpa::whereHas('spd', function ($query) use ($sekretariatDaerah, $tahun) {
                $query->where('tahun_id', $tahun);
                $query->where('sekretariat_daerah_id', $sekretariatDaerah->id);
            })->get();

            foreach ($kegiatanDpa as $kegiatan) {
                for ($i = 0; $i < count($bulan); $i++) {
                    $perencanaanAnggaran = rand(50000000, 100000000);
                    $anggaranDigunakan = rand(50000000, $perencanaanAnggaran);

                    $sppGu = new SppGu();
                    $sppGu->tahun_id = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';
                    $sppGu->kegiatan_id = $kegiatan->id;
                    $sppGu->sekretariat_daerah_id = $sekretariatDaerah->id;
                    $sppGu->tahap_riwayat = 2;
                    $sppGu->bulan = $bulan[$i];
                    $sppGu->perencanaan_anggaran = $perencanaanAnggaran;
                    $sppGu->anggaran_digunakan = $anggaranDigunakan;
                    $sppGu->nomor_surat = '101-02-2003-SPP-GU';
                    $sppGu->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                    $sppGu->tahap = 'Selesai';
                    $sppGu->surat_penolakan = NULL;
                    $sppGu->status_validasi_asn = 1;
                    $sppGu->alasan_validasi_asn = NULL;
                    $sppGu->status_validasi_ppk = 1;
                    $sppGu->alasan_validasi_ppk = NULL;
                    $sppGu->tanggal_validasi_asn = '2023-07-24';
                    $sppGu->tanggal_validasi_ppk = '2023-07-24';
                    $sppGu->status_validasi_akhir = 1;
                    $sppGu->tanggal_validasi_akhir = '2023-07-24';
                    $sppGu->dokumen_spm = '1690178937.pdf';
                    $sppGu->dokumen_arsip_sp2d = '1690178943.pdf';
                    $sppGu->created_at = '2023-' . ($i + 1) . '-24 06:02:55';
                    $sppGu->updated_at = '2023-' . ($i + 1) . '-24 06:09:03';
                    $sppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = '81a75b22-5e0a-4a57-ac63-e2c28dec2612';
                    $riwayatSppGu->perencanaan_anggaran = $perencanaanAnggaran;
                    $riwayatSppGu->anggaran_digunakan = $anggaranDigunakan;
                    $riwayatSppGu->tahap_riwayat = 2;
                    $riwayatSppGu->role = 'ASN Sub Bagian Keuangan';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Disetujui';
                    $riwayatSppGu->created_at = '2023-07-24 06:06:35';
                    $riwayatSppGu->updated_at = '2023-07-24 06:06:35';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                    $riwayatSppGu->perencanaan_anggaran = NULL;
                    $riwayatSppGu->anggaran_digunakan = NULL;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = 'Admin';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Upload SPM';
                    $riwayatSppGu->created_at = '2023-07-24 06:08:57';
                    $riwayatSppGu->updated_at = '2023-07-24 06:08:57';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = '81a75b22-5e0a-4a57-ac63-e2c28dec2612';
                    $riwayatSppGu->perencanaan_anggaran = 89000000;
                    $riwayatSppGu->anggaran_digunakan = 0;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = 'ASN Sub Bagian Keuangan';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Disetujui';
                    $riwayatSppGu->created_at = '2023-07-24 06:05:34';
                    $riwayatSppGu->updated_at = '2023-07-24 06:05:34';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = '049e4da4-eb05-4216-a422-277703af887e';
                    $riwayatSppGu->perencanaan_anggaran = 89000000;
                    $riwayatSppGu->anggaran_digunakan = 0;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = 'PPK';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Disetujui';
                    $riwayatSppGu->created_at = '2023-07-24 06:05:54';
                    $riwayatSppGu->updated_at = '2023-07-24 06:05:54';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                    $riwayatSppGu->perencanaan_anggaran = NULL;
                    $riwayatSppGu->anggaran_digunakan = NULL;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = 'Admin';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Upload Arsip SP2D';
                    $riwayatSppGu->created_at = '2023-07-24 06:09:03';
                    $riwayatSppGu->updated_at = '2023-07-24 06:09:03';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = '049e4da4-eb05-4216-a422-277703af887e';
                    $riwayatSppGu->perencanaan_anggaran = 89000000;
                    $riwayatSppGu->anggaran_digunakan = 80000000;
                    $riwayatSppGu->tahap_riwayat = 2;
                    $riwayatSppGu->role = 'PPK';
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Disetujui';
                    $riwayatSppGu->created_at = '2023-07-24 06:06:50';
                    $riwayatSppGu->updated_at = '2023-07-24 06:06:50';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                    $riwayatSppGu->perencanaan_anggaran = 89000000;
                    $riwayatSppGu->anggaran_digunakan = NULL;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = NULL;
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Dibuat';
                    $riwayatSppGu->created_at = '2023-07-24 06:02:55';
                    $riwayatSppGu->updated_at = '2023-07-24 06:02:55';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = 'efac625e-df6e-4c75-8b6a-27cece457709';
                    $riwayatSppGu->perencanaan_anggaran = NULL;
                    $riwayatSppGu->anggaran_digunakan = 80000000;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = NULL;
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Upload Tahap SPP';
                    $riwayatSppGu->created_at = '2023-07-24 06:06:19';
                    $riwayatSppGu->updated_at = '2023-07-24 06:06:19';
                    $riwayatSppGu->save();

                    $riwayatSppGu = new RiwayatSppGu();
                    $riwayatSppGu->spp_gu_id = $sppGu->id;
                    $riwayatSppGu->user_id = '049e4da4-eb05-4216-a422-277703af887e';
                    $riwayatSppGu->perencanaan_anggaran = NULL;
                    $riwayatSppGu->anggaran_digunakan = 80000000;
                    $riwayatSppGu->tahap_riwayat = 1;
                    $riwayatSppGu->role = NULL;
                    $riwayatSppGu->nomor_surat = NULL;
                    $riwayatSppGu->alasan = NULL;
                    $riwayatSppGu->surat_penolakan = NULL;
                    $riwayatSppGu->status = 'Diselesaikan';
                    $riwayatSppGu->created_at = '2023-07-24 06:08:43';
                    $riwayatSppGu->updated_at = '2023-07-24 06:08:43';
                    $riwayatSppGu->save();
                }
            }
        }
    }
}
