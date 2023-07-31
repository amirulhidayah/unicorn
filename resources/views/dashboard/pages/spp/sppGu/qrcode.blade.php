<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR CODE</title>

    <style>
        .fw-bold {
            font-weight: bold;
        }

        .my-0 {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>

<body>
    @component('dashboard.components.widgets.info', [
        'judul' => 'Id',
        'isi' => strtoupper($sppGu->id),
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Sekretariat Daerah',
        'isi' => $sppGu->SekretariatDaerah->nama,
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Nomor Surat',
        'isi' => $sppGu->nomor_surat,
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Tahun',
        'isi' => $sppGu->tahun->tahun,
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Bulan',
        'isi' => $sppGu->bulan,
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Program',
        'isi' => $sppGu->kegiatanDpa->programDpa->nama . ' (' . $sppGu->kegiatanDpa->programDpa->no_rek . ')',
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Kegiatan',
        'isi' => $sppGu->kegiatanDpa->nama . ' (' . $sppGu->kegiatanDpa->no_rek . ')',
    ])
    @endcomponent
    @component('dashboard.components.widgets.info', [
        'judul' => 'Anggaran Yang Direncanakan',
        'isi' => $perencanaanAnggaran,
    ])
    @endcomponent
    <img src="data:image/png;base64, {!! base64_encode(
        QrCode::format('svg')->size(140)->errorCorrection('H')->generate(strtoupper($sppGu->id)),
    ) !!}">
</body>

</html>
