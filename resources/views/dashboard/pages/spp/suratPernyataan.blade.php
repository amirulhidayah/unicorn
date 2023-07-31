<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .text-justify {
            text-align: justify;
        }

        .text-center {
            text-align: center;
        }

        p {
            margin: 0px 0px;
            font-size: 12px;
            line-height: 23px;
        }

        .tanda-tangan {
            margin-bottom: -30px;
            margin-top: -30px;
            display: block;
            height: 100px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .indent {
            text-indent: 50px;
        }

        .text-underline {
            text-decoration: underline;
        }

        #tabel-ttd p {
            line-height: 15px;
        }

        body {
            padding: 25px 50px;
        }
    </style>
    <title>Surat Pernyataan</title>
</head>

<body>
    <p class="text-center">SURAT PERNYATAAN VERIFIKASI</p>
    <p class="text-center">{{ $tipe }} PENGADAAN BARANG DAN JASA</p>
    <p class="text-center fw-bold">Nomor : {{ $spp->nomor_surat }}</p>

    <br>
    <br>

    <p class="text-justify indent">Berdasarkan Peraturan Pemerintah Nomor 12 Tahun 2019 tentang Pengelolaan Keuangan
        Daerah,
        Peraturan Menteri Dalam Negeri Nomor 77 Tahun 2020 tentang Pedoman Pelaksanaan Pengelolaan Keuangan Daerah,
        dengan ini PPK SKPD pada Sekretariat Daerah Provinsi Sulawesi Tengah menyatakan bahwa dokumen pengajuan
        {{ $tipe }} Pengadaan Barang dan Jasa Nomor : {{ $spp->nomor_surat }}, Tanggal {{ $tanggal }},
        Jumlah {{ $jumlah }} telah selesai
        diverifikasi atas
        kelengkapan dokumen tersebut.</p>
    <p>Demikian surat pernyataan ini dibuat dengan sebenarnya.</p>

    <table width="100%" id="tabel-ttd">
        <tr>
            <td width="50%">
            </td>
            <td width="50%" class="text-center">
                <br>
                <br>
                <p>Palu, {{ $hariIni }}</p>
                <br>
                <p>PPK Sekretariat Daerah
                </p>
                <p>Provinsi Sulawesi Tengah,</p>
                <img src="{{ storage_path('app/public/tanda_tangan/' . $ppk->profil->tanda_tangan) }}" alt=""
                    class="tanda-tangan">
                <p class="fw-bold text-underline">{{ $ppk->profil->nama }}</p>
                <p class="fw-bold">NIP. {{ $ppk->profil->nip }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
