@extends('dashboard.layouts.main')

@section('title')
    SPP UP
@endsection

@push('style')
    <style>
        .box-upload .card-body {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }

        .box-upload {
            border-radius: 10px;
            border: 1px solid rgb(24, 23, 23, 0.15);
        }

        .box-upload .card-footer {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }

        .card-profile .card-header {
            background: rgb(34, 195, 80);
            background: linear-gradient(101deg, rgba(34, 195, 80, 1) 0%, rgba(253, 187, 45, 1) 100%);
        }

        #card-tambah {
            cursor: pointer;
        }

        #card-keterangan-upload {
            border-style: dashed;
            border-color: grey;
            border-width: 1px;
        }

        #card-keterangan-upload .card-body {
            padding-top: 30px;
            padding-bottom: 30px;
        }
    </style>
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
            <a href="#">SPP UP</a>
        </li>
    </ul>
@endsection

@section('content')
    <form id="form-tambah" enctype="multipart/form-data">
        {{ csrf_field() }}
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Edit SPP UP</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Sekretariat Daerah',
                                    'isi' => $sppUp->SekretariatDaerah->nama,
                                ])
                                @endcomponent
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Nomor Surat Permintaan Pembayaran (SPP)',
                                    'isi' => $sppUp->nomor_surat,
                                ])
                                @endcomponent
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Tahun',
                                    'isi' => $sppUp->tahun->tahun,
                                ])
                                @endcomponent
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Program',
                                    'isi' => $sppUp->kegiatan->program->nama . ' (' . $sppUp->kegiatan->program->no_rek . ')',
                                ])
                                @endcomponent
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Kegiatan',
                                    'isi' => $sppUp->kegiatan->nama . ' (' . $sppUp->kegiatan->no_rek . ')',
                                ])
                                @endcomponent
                                <div class="col-12">
                                    @component('dashboard.components.formElements.input', [
                                        'label' => 'Jumlah Anggaran',
                                        'type' => 'text',
                                        'id' => 'jumlah_anggaran',
                                        'name' => 'jumlah_anggaran',
                                        'class' => 'uang',
                                        'value' => $sppUp->jumlah_anggaran,
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Jumlah Anggaran',
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if ($sppUp->alasan_validasi_asn != null)
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'asn',
                                        'tanggal' => $sppUp->tanggal_validasi_asn,
                                        'isi' => $sppUp->alasan_validasi_asn,
                                    ])
                                    @endcomponent
                                @endif

                                @if ($sppUp->alasan_validasi_ppk != null)
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'ppk',
                                        'tanggal' => $sppUp->tanggal_validasi_ppk,
                                        'isi' => $sppUp->alasan_validasi_ppk,
                                    ])
                                    @endcomponent
                                @endif

                                <div class="card" id="card-keterangan-upload">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-upload" style="font-size: 75px"></i>
                                        <p class="my-0">Upload Dokumen</p>
                                        <p class="my-0">Ukuran Maksimum File Adalah <span class="text-danger">5
                                                MB</span> Dengan Tipe File
                                            <span class="text-danger">PDF</span>
                                        </p>
                                    </div>
                                </div>
                                <div id="list-upload">
                                    @if (!($sppUp->status_validasi_ppk == 0 && $sppUp->status_validasi_asn == 0))
                                        <div class="card box-upload" class="box-upload">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- <div class="d-flex border rounded shadow shadow-lg p-2 "> -->
                                                    <div class="col-3 d-flex align-items-center justify-content-center">
                                                        <img src="{{ asset('assets/dashboard/img/pdf.png') }}"
                                                            alt="" width="70px">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="mb-3 mt-2">
                                                            <p class="fw-bold" style="font-size: 15px;">Surat Penolakan
                                                            </p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input name="surat_penolakan" class="form-control"
                                                                type="file" accept="application/pdf">
                                                            <p class="text-danger error-text surat_penolakan-error my-0">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fw-bold card-footer bg-primary text-light text-center p-0">
                                                ! Wajib Dimasukan</div>
                                        </div>
                                    @endif
                                    <hr>
                                    <small class="text-danger error-text dokumenFileHitung-error"
                                        id="dokumenFileHitung-error"></small>
                                    @foreach ($sppUp->dokumenSppUp as $dokumen)
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'labelNama' => $dokumen->nama_dokumen,
                                            'nameFileDokumen' => $dokumen->id,
                                            'classDokumen' => 'file_dokumen_update',
                                            'classNama' => 'nama_file_update',
                                        ])
                                        @endcomponent
                                    @endforeach
                                </div>

                                <div class="card bg-primary" id="card-tambah">
                                    <div class="card-body text-light text-center">
                                        <i class="fas fa-plus-circle fa-2xl" style="font-size: 75px"></i>
                                        <p class="my-0 fw-bold">Tambah Dokumen</p>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success col-12 text-light fw-bold"><i
                                        class="far fa-paper-plane"></i>
                                    Upload
                                    Dokumen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#spp-up').addClass('active');
        })

        function hapus(id) {
            $('#box-upload-' + id).fadeOut("slow", function() {
                $("#box-upload-" + id).remove();
            });
        }

        $('#card-tambah').click(function() {
            $.ajax({
                type: "GET",
                url: "{{ url('append/spp') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#list-upload').append(response.html);
                    } else {
                        swal("Periksa Kembali Data Anda", {
                            buttons: false,
                            timer: 1500,
                            icon: "warning",
                        });
                    }
                },
                error: function(response) {
                    swal("Gagal", "Terjadi Kesalahan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                },
            });
        })

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $('.file_dokumen_update').each(function() {
                var nama = $(this).attr('name');
                formData.append('fileDokumenUpdate[]', nama);
            });

            $('.nama_file_update').each(function() {
                var nama = $(this).attr('name');
                formData.append('namaFileUpdate[]', nama);
            });

            $('.file_dokumen').each(function() {
                var nama = $(this).attr('name');
                formData.append('fileDokumen[]', nama);
            });

            $('.nama_file').each(function() {
                var nama = $(this).attr('name');
                formData.append('namaFile[]', nama);
            });

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
                        url: "{{ url('spp-up/' . $sppUp->id) }}",
                        type: "POST",
                        data: formData,
                        async: false,
                        success: function(response) {
                            if (response.status == "success") {
                                swal("Berhasil",
                                    "Dokumen berhasil diubah", {
                                        button: false,
                                        icon: "success",
                                    });
                                setTimeout(
                                    function() {
                                        $(location).attr('href', "{{ url('spp-up') }}");
                                    }, 2000);
                            } else {
                                swal("Periksa Kembali Data Anda", {
                                    buttons: false,
                                    timer: 1500,
                                    icon: "warning",
                                });
                                console.log(response);
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                            swal("Gagal", "Terjadi Kesalahan", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });
        })
    </script>
@endpush
