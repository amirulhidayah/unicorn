<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Penolakan SPP LS</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> --}}
    <style>
        p {
            margin: 0px;
            font-size: 12px;
            font-family: "Times New Roman", Times, serif;
            line-height: 18px;
        }

        .tanda-tangan {
            margin-bottom: -30px;
            margin-top: -30px;
            display: block;
            height: 100px;
        }

        table td,
        table td * {
            vertical-align: top;
        }

        .nama-ttd {
            font-weight: bold;
        }

        .nip-ttd {
            margin-top: -3px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td width="10%">
                <p>Nomor : </p>
                <p>Perihal : </p>
            </td>
            <td width="60%">
                <p>{{ $riwayatSppLs->nomor_surat }}</p>
                <p><b>Pengembalian SPJ/SPP</b></p>
            </td>
            <td width="30%">
                <p>Palu,</p>
                <p>Kepada Yth.</p>
                <p>PPTK / Bendahara</p>
                <br>
                <p>Biro Umum</p>
                <p>Di-Tempat</p>
            </td>
        </tr>
    </table>
    <p style="margin: 5px 0">Bersama ini terlampir : </p>

    <table width="100%">
        <tr>
            <td width="20%">
                <p>- SPP-TU Nomor</p>
                <p>- Tanggal</p>
                <p>- SPJ Kegiatan</p>
                {{-- <p>- Jumlah Anggaran : Rp. {{ number_format($riwayatSppLs->anggaran_digunakan, 0, ',', '.') }}</p> --}}
            </td>
            <td width="50%">
                <p> : {{ $riwayatSppLs->sppLs->nomor_surat }}</p>
                <p> : {{ \Carbon\Carbon::parse($riwayatSppLs->created_at)->translatedFormat('d F Y') }}</p>
                <p> : {{ $riwayatSppLs->sppLs->kegiatan->nama }}</p>
            </td>
            <td width="5%">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>Jumlah</p>
            </td>
            <td width="25%">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p> : Rp. {{ number_format($riwayatSppLs->anggaran_digunakan, 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>

    <p style="margin-top: 10px">Dikembalikan karena tidak memenuhi syarat untuk diproses. Adapun kekurangannya adalah
        sebagai berikut
        :</p>
    <p style="margin: 13px 0"><i>1. Catatan Verifikator : </i></p>


    <table style="border: 2px solid black;" width="100%">
        <tr>
            <td width="100%" style="padding: 13px">
                <p>{{ $riwayatSppLsAsn->alasan ?? '' }}</p>
            </td>
        </tr>
    </table>

    <p style="margin: 13px 0"><i>2. Catatan Pejabat Penatausahaan Keuangan : </i></p>


    <table style="border: 2px solid black;" width="100%">
        <tr>
            <td width="100%" style="padding: 13px">
                <p>{{ $riwayatSppLsPpk->alasan ?? '' }}</p>
            </td>
        </tr>
    </table>
    <br>
    <br>

    <table width="100%" style="margin: 0px;padding : 0px">
        <tr>
            <td width="50%" style="text-align: center">
                <p>&nbsp;</p>
                <p>PPK Biro</p>
                <img src="{{ storage_path('app/public/tanda_tangan/' . $ppk->profil->tanda_tangan) }}" alt=""
                    class="tanda-tangan">
                <p class="nama-ttd"><u>{{ $ppk->profil->nama }}</u></p>
                <p class="nip-ttd">NIP. {{ $ppk->profil->nip }}</p>
            </td>
            <td width="50%" style="text-align: center">
                <p>Palu, {{ $hariIni }}</p>
                <p>Kuasa Pengguna Anggaran</p>
                <img src="{{ storage_path('app/public/tanda_tangan/' . $kuasaPenggunaAnggaran->profil->tanda_tangan) }}"
                    alt="" class="tanda-tangan">
                <p class="nama-ttd"><u>{{ $kuasaPenggunaAnggaran->profil->nama }}</u></p>
                <p class="nip-ttd">NIP. {{ $kuasaPenggunaAnggaran->profil->nip }}</p>
            </td>
        </tr>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</body>

</html>
