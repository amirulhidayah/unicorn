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
            <a href="#">SPP</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">SPP GU</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Detail Dokumen</div>
                        <div class="card-tools">
                            @if ((Auth::user()->role == 'PPK' && $sppGu->status_validasi_ppk == 0 && Auth::user()->is_aktif == 1) || ($sppGu->status_validasi_asn == 0 && Auth::user()->role == 'ASN Sub Bagian Keuangan' && Auth::user()->is_aktif == 1))
                                @component('dashboard.components.buttons.verifikasi',
                                    [
                                        'id' => 'btn-verifikasi',
                                        'class' => '',
                                        'url' => '/anc/create',
                                        'type' => 'button',
                                    ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Tahap',
                                    'isi' => $tahap,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Sekretariat Daerah',
                                    'isi' => $sppGu->biroOrganisasi->nama,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Nomor Surat',
                                    'isi' => $sppGu->nomor_surat,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Tahun',
                                    'isi' => $sppGu->tahun->tahun,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Bulan',
                                    'isi' => $sppGu->bulan,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Program',
                                    'isi' => $sppGu->kegiatan->program->nama . ' (' . $sppGu->kegiatan->program->no_rek . ')',
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Kegiatan',
                                    'isi' => $sppGu->kegiatan->nama . ' (' . $sppGu->kegiatan->no_rek . ')',
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Jumlah Anggaran',
                                    'isi' => $jumlahAnggaran,
                                ])
                            @endcomponent
                            @component('dashboard.components.widgets.info',
                                [
                                    'judul' => 'Anggaran Yang Digunakan',
                                    'isi' => $anggaranDigunakan,
                                ])
                            @endcomponent
                        </div>
                        <div class="col-lg-6">
                            @component('dashboard.components.widgets.listDokumen',
                                [
                                    'dokumenSpp' => $daftarDokumenSppGu,
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
        const printErrorMsg = (msg) => {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').text(value);
            });
        }

        $(document).ready(function() {
            $('#spp-gu').addClass('active');
        })
    </script>
@endpush
