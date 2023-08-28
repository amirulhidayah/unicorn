@extends('dashboard.layouts.main')

@section('title')
    Repositori SPP LS
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
            <a href="#">Repositori</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">SPP LS</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Detail SPP LS</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Kategori',
                                'isi' => $sppLs->kategoriSppLs->nama,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Nomor Surat Permintaan Pembayaran (SPP)',
                                'isi' => $sppLs->nomor_surat,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Sekretariat Daerah',
                                'isi' => $sppLs->sekretariatDaerah->nama,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Tahun',
                                'isi' => $sppLs->tahun->tahun,
                            ])
                            @endcomponent
                            @component('dashboard.components.widgets.info', [
                                'judul' => 'Bulan',
                                'isi' => $sppLs->bulan,
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($programDanKegiatan as $kegiatanSppLs)
                                            <tr>
                                                <td>{{ $kegiatanSppLs['program'] }}
                                                </td>
                                                <td>{{ $kegiatanSppLs['kegiatan'] }}
                                                </td>
                                                <td>{{ number_format($kegiatanSppLs['anggaran_digunakan'] ?? 0, 0, ',', '.') }}
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
                            <div class="col-12 mb-4">
                                <p class="h4 my-3 fw-bold">Dokumen</p>
                                @component('dashboard.components.widgets.listDokumen', [
                                    'dokumenSpp' => $sppLs->dokumenSppLs,
                                    'spp' => $sppLs,
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
                                        onclick="openPdfInFullscreen('{{ Storage::url('dokumen_spm_spp_ls/' . $sppLs->dokumen_spm) }}')"
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
                                        onclick="openPdfInFullscreen('{{ Storage::url('dokumen_arsip_sp2d_spp_ls/' . $sppLs->dokumen_arsip_sp2d) }}')"
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
            $('#repositori-spp-ls').addClass('active');
        })
    </script>
@endpush
