@extends('dashboard.layouts.main')

@section('title')
    Repositori SPP GU
@endsection

@push('style')
@endpush

@section('breadcrumb')
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="#">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Repositori SPP GU</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">SPP</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Detail SPP GU</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Nomor Surat Permintaan Pembayaran (SPP)',
                                'isi' => $sppGu->nomor_surat,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Sekretariat Daerah',
                                'isi' => $sppGu->spjGu->sekretariatDaerah->nama,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Tahun',
                                'isi' => $sppGu->spjGu->tahun->tahun,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Nomor Surat Pertanggungjawaban (SPJ)',
                                'isi' => $sppGu->spjGu->nomor_surat,
                            ])
                            @endcomponent
                            <div class="col-lg-12 my-3">
                                <div class="border border-black rounded p-4">
                                    @component('dashboard.components.widgets.info', [
                                        'judul' => 'Nomor Surat Pertanggungjawaban (SPJ)',
                                        'isi' => $spjGu->nomor_surat,
                                    ])
                                    @endcomponent
                                    @component('dashboard.components.widgets.info', [
                                        'judul' => 'Sekretariat Daerah',
                                        'isi' => $spjGu->sekretariatDaerah->nama,
                                    ])
                                    @endcomponent
                                    @component('dashboard.components.widgets.info', [
                                        'judul' => 'Tahun',
                                        'isi' => $spjGu->tahun->tahun,
                                    ])
                                    @endcomponent
                                    @component('dashboard.components.widgets.info', [
                                        'judul' => 'Bulan',
                                        'isi' => $spjGu->bulan,
                                    ])
                                    @endcomponent
                                    <div class="col-12 mb-4">
                                        <p class="h4 my-3 fw-bold">Program dan Kegiatan</p>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Program</th>
                                                    <th scope="col">Kegiatan</th>
                                                    <th scope="col">Anggaran Digunakan</th>
                                                    <th scope="col">Dokumen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($programDanKegiatan as $kegiatanSpjGu)
                                                    <tr>
                                                        <td>{{ $kegiatanSpjGu['program'] }}
                                                        </td>
                                                        <td>{{ $kegiatanSpjGu['kegiatan'] }}
                                                        </td>
                                                        <td>{{ number_format($kegiatanSpjGu['anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <a target="_blank"
                                                                href="{{ Storage::url('dokumen_spj_gu/' . $kegiatanSpjGu['dokumen']) }}"
                                                                class="btn btn-primary btn-sm mt-1"><i
                                                                    class="fas fa-file-pdf"></i>
                                                                Lihat</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2" class="fw-bold text-center">Total</td>
                                                    <td>{{ number_format($totalProgramDanKegiatan['total_anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <p class="h4 my-3 fw-bold">Dokumen</p>
                                @component('dashboard.components.widgets.listDokumen', [
                                    'dokumenSpp' => $sppGu->dokumenSppGu,
                                    'spp' => $sppGu,
                                    'tipe' => $tipe,
                                ])
                                @endcomponent
                                <li class="media mb-3 d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="35px">
                                    <div class="media-body">
                                        <h5 class="font-16 mb-1 ml-2 my-0 mr-1 fw-bold">SPM<i
                                                class="feather icon-download-cloud float-right"></i></h5>
                                    </div>
                                    <button
                                        onclick="openPdfInFullscreen('{{ Storage::url('dokumen_spm_spp_gu/' . $sppGu->dokumen_spm) }}')"
                                        class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i>
                                        Lihat</button>
                                </li>
                                <li class="media mb-3 d-flex align-items-center">
                                    <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="35px">
                                    <div class="media-body">
                                        <h5 class="font-16 mb-1 ml-2 my-0 mr-1 fw-bold">Arsip SP2D<i
                                                class="feather icon-download-cloud float-right"></i></h5>
                                    </div>
                                    <button
                                        onclick="openPdfInFullscreen('{{ Storage::url('dokumen_arsip_sp2d_spp_gu/' . $sppGu->dokumen_arsip_sp2d) }}')"
                                        class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i>
                                        Lihat</button>
                                </li>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#menu-repositori-spp-gu').collapse('show');
            $('#repositori-spp-gu').addClass('active');
            $('#repositori-spp-gu-spp').addClass('active');
        })
    </script>
@endpush