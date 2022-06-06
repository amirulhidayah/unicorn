<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Penolakan SPP-LS</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> --}}
    <style>
        p {
            margin: 0px;
            font-size: 13px;
        }

        #tanda-tangan {
            margin-bottom: -50px;
            margin-top: -50px;
        }

    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td width="50%">
                <p>Nomor : {{ $riwayatSppLs->nomor_surat }}</p>
                <p>Perihal : <b>Pengembalian SPJ/SPP</b></p>
            </td>
            <td width="50%">
                <p>Palu,</p>
                <p>Kepada Yth.</p>
                <p>PPTK/Bendahara</p>
                <br>
                <p>Biro Umum</p>
                <p>Di-Tempat</p>
            </td>
        </tr>

        <tr>
            <td>
                <br>
                <br>
                <br>
                <p>Bersama ini terlampir : </p>
            </td>
        </tr>

        <tr>
            <td width="75%">
                <p>- SPP-LS Nomor : {{ $riwayatSppLs->sppLs->nomor_surat }}</p>
                <p>- SPJ Kegiatan : {{ $riwayatSppLs->sppLs->kegiatan->nama }}</p>
                <p> - SPJ Kategori : {{ $riwayatSppLs->sppLs->kategori }}</p>
            </td>
            <td width="25%">
                <p>Tanggal : {{ \Carbon\Carbon::parse($riwayatSppLs->created_at)->translatedFormat('d F Y') }}</p>
                <p>Jumlah : {{ 'Rp. ' . number_format($riwayatSppLs->anggaran_digunakan, 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>


    <table>
        <tr>
            <td>
                <br>
                <br>
                <br>
                <p>Dikembalikan karena tidak memenuhi syarat untuk diproses. Adapun kekurangannya adalah sebagai berikut
                    :</p>
                <br>
                <p><i> Catatan : </i></p>
                <br>
            </td>
        </tr>

    </table>

    <table style="border: 2px solid black;" width="100%">
        <tr>
            <td width="100%" style="padding: 20px">
                <p>{{ $riwayatSppLs->alasan }}</p>
            </td>
        </tr>
    </table>
    <br>
    <br>

    <table width="100%">
        <tr>
            <td width="70%">
            </td>
            <td width="30%" style="text-align: center">
                <p>Palu, {{ $hariIni }}</p>
                <img src="{{ Storage::url('tanda_tangan/' . $riwayatSppLs->profil->tanda_tangan) }}" alt=""
                    id="tanda-tangan">
                <p class="fw-bold"><u>{{ $riwayatSppLs->profil->nama }}</u></p>
                <p>{{ $riwayatSppLs->user->role }}</p>
            </td>
        </tr>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</body>

</html>
