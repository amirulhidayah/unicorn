@extends('dashboard.layouts.main')

@section('title')
    SPP GU
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
            <a href="#">SPP GU</a>
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
                        <div class="card-tools">
                            @if (in_array(Auth::user()->role, ['PPK', 'ASN Sub Bagian Keuangan']) && $sppGu->status_validasi_akhir == 0)
                                @component('dashboard.components.buttons.verifikasi', [
                                    'id' => 'btn-verifikasi',
                                    'class' => '',
                                    'type' => 'button',
                                ])
                                @endcomponent
                            @endif
                        </div>
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
                                                    <th scope="col">Jumlah Anggaran</th>
                                                    <th scope="col">Anggaran Digunakan</th>
                                                    <th scope="col">Sisa Anggaran</th>
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
                                                        <td>{{ number_format($kegiatanSpjGu['jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($kegiatanSpjGu['anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($kegiatanSpjGu['sisa_anggaran'] ?? 0, 0, ',', '.') }}
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
                                                    <td>{{ number_format($totalProgramDanKegiatan['total_jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($totalProgramDanKegiatan['total_anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($totalProgramDanKegiatan['total_sisa_anggaran'] ?? 0, 0, ',', '.') }}
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
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" id="form-validasi" enctype="multipart/form-data">
        @component('dashboard.components.modals.verifikasi')
        @endcomponent
    </form>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#div-alasan').hide();
        })

        $('#btn-verifikasi').click(function() {
            $('#form-validasi').trigger("reset");
            $('#modal-verifikasi').modal('show');
        });

        $('#verifikasi').on('change', function() {
            if ($(this).val() == 1) {
                $('#div-alasan').hide();
            } else {
                $('#div-alasan').show();
            }
        })

        $('#form-validasi').submit(function(e) {
            e.preventDefault();

            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'warning',
                text: "Apakah Anda Yakin ?",
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Update) => {
                if (Update) {
                    $.ajax({
                        type: 'PUT',
                        url: "{{ url('spp-gu/verifikasi/' . $sppGu->id) }}",
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-verifikasi').modal('hide');
                                swal("Berhasil", "Data Berhasil Disimpan", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                }).then(function() {
                                    window.location.href =
                                        "{{ url('spp-gu') }}";
                                })
                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal({
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat memproses data',
                                type: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            })
                        }
                    })
                }
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#menu-spp-gu').collapse('show');
            $('#spp-gu').addClass('active');
            $('#spp-gu-spp').addClass('active');
        })
    </script>
@endpush
