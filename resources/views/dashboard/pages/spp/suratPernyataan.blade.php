<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        body {
            padding: 100px;

        }

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
            text-indent: 13px;
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
    <p class="text-center fw-bold">SURAT PERNYATAAN VERIFIKASI</p>
    <p class="text-center fw-bold">{{ $tipe }}</p>

    <br>
    <br>

    <p class="text-justify indent">
        Berdasarkan Peraturan Pemerintah Nomor 12 Tahun 2019 tentang Pengelolaan Keuangan Daerah, Peraturan Menteri
        Dalam Negeri Nomor 77 Tahun 2020 tentang Pedoman Teknis Pengelolaan Keuangan Daerah. Dengan ini Pejabat
        Penatausahaan Keuangan Sekretariat Daerah Provinsi Sulawesi Tengah menyatakan bahwa :
    </p>

    <table>
        <tr>
            <td style="vertical-align: top;">
                <p> 1.</p>
            </td>
            <td>
                <p>Sudah melakukan verifikasi beserta bukti kelengkapannya yang diajukan Bendahara Pengeluaran/Bendahara
                    Pengeluaran Pembantu, {{ $tipe }} Nomor : <span class="fw-bold">
                        {{ $spp->nomor_surat }}</span></p>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <p> 2.</p>
            </td>
            <td>
                <p>Sudah Menyiapkan Surat Permintaan Membayar (SPM)</p>
            </td>
        </tr>
    </table>

    <br>
    <p class="indent"> Demikian Surat Pernyataan Verifikasi ini dibuat dan dapat dipertanggungjawabkan.</p>

    <table width="100%" id="tabel-ttd">
        <tr>
            <td width="50%" class="text-center" style="vertical-align: middle">
                <img style="margin-top: 50px" src="data:image/png;base64, {!! base64_encode(
                    QrCode::format('svg')->size(80)->errorCorrection('H')->generate(strtoupper($tipe . '-' . $spp->id)),
                ) !!}">
                <p style="margin-top: 10px">{{ strtoupper($tipe . '-' . $spp->id) }}</p>
            </td>
            <td width="50%" class="text-center">
                <br>
                <br>
                <p>Palu, {{ $hariIni }}</p>
                <br>
                <p>Pejabat Penatausahaan Keuangan
                </p>
                <p>Sekretariat Daerah Prov. Sulteng,</p>
                <img src="{{ storage_path('app/public/tanda_tangan/' . $ppk->profil->tanda_tangan) }}" alt=""
                    class="tanda-tangan">
                <p class="fw-bold text-underline">{{ $ppk->profil->nama }}</p>
                <p class="fw-bold">NIP. {{ $ppk->profil->nip }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
