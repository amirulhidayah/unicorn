<div class="col-lg-6">
    @component('dashboard.components.widgets.info', [
        'judul' => 'Sekretariat Daerah',
        'isi' => $sppGu->sekretariatDaerah->nama,
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
</div>
<div class="col-lg-6">
    @foreach ($dokumenSppGu as $dokumen)
        <li class="media mb-3 d-flex align-items-center">
            <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="35px">
            <div class="media-body">
                <h5 class="font-16 mb-1 ml-2 my-0 mr-1 fw-bold">{{ $dokumen->nama_dokumen }}<i
                        class="feather icon-download-cloud float-right"></i></h5>
            </div>
            <button onclick="openPdfInFullscreen('{{ Storage::url('dokumen_spp_gu/' . $dokumen->dokumen) }}')"
                class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i>
                Lihat</button>
        </li>
    @endforeach
</div>
