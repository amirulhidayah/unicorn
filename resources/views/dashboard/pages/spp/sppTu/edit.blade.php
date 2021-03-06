@extends('dashboard.layouts.main')

@section('title')
    SPP TU
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
            <a href="#">SPP TU</a>
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
                            <div class="card-title">Edit SPP TU</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Sekretariat Daerah',
                                        'isi' => $sppTu->biroOrganisasi->nama,
                                    ])
                                @endcomponent
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Nomor Surat',
                                        'isi' => $sppTu->nomor_surat,
                                    ])
                                @endcomponent
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Tahun',
                                        'isi' => $sppTu->tahun->tahun,
                                    ])
                                @endcomponent
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Bulan',
                                        'isi' => $sppTu->bulan,
                                    ])
                                @endcomponent
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Program',
                                        'isi' => $sppTu->kegiatan->program->nama . ' (' . $sppTu->kegiatan->program->no_rek . ')',
                                    ])
                                @endcomponent
                                @component('dashboard.components.widgets.info',
                                    [
                                        'judul' => 'Kegiatan',
                                        'isi' => $sppTu->kegiatan->nama . ' (' . $sppTu->kegiatan->no_rek . ')',
                                    ])
                                @endcomponent
                                <div class="col-12">
                                    @component('dashboard.components.formElements.input',
                                        [
                                            'label' => 'Jumlah Anggaran',
                                            'type' => 'text',
                                            'id' => 'jumlah_anggaran',
                                            'name' => 'jumlah_anggaran',
                                            'class' => 'uang',
                                            'value' => $sppTu->jumlah_anggaran,
                                            'wajib' => '<sup class="text-danger">*</sup>',
                                            'placeholder' => 'Masukkan Jumlah Anggaran',
                                        ])
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if ($sppTu->alasan_validasi_asn != null)
                                    @component('dashboard.components.widgets.alert',
                                        [
                                            'oleh' => 'asn',
                                            'tanggal' => $sppTu->tanggal_validasi_asn,
                                            'isi' => $sppTu->alasan_validasi_asn,
                                        ])
                                    @endcomponent
                                @endif

                                @if ($sppTu->alasan_validasi_ppk != null)
                                    @component('dashboard.components.widgets.alert',
                                        [
                                            'oleh' => 'ppk',
                                            'tanggal' => $sppTu->tanggal_validasi_ppk,
                                            'isi' => $sppTu->alasan_validasi_ppk,
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
                                    <div class="card box-upload" class="box-upload">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- <div class="d-flex border rounded shadow shadow-lg p-2 "> -->
                                                <div class="col-3 d-flex align-items-center justify-content-center">
                                                    <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt=""
                                                        width="70px">
                                                </div>
                                                <div class="col-9">
                                                    <div class="mb-3 mt-2">
                                                        <p class="fw-bold" style="font-size: 15px;">Surat Penolakan
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input name="surat_penolakan" class="form-control" type="file"
                                                            multiple="true">
                                                        <p class="text-danger error-text surat_penolakan-error my-0">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fw-bold card-footer bg-primary text-light text-center p-0">
                                            ! Wajib Dimasukan</div>
                                    </div>
                                    <hr>
                                    @foreach ($sppTu->dokumenSppTu as $dokumen)
                                        <div class="card box-upload" id="box-upload-{{ $loop->iteration }}"
                                            class="box-upload">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- <div class="d-flex border rounded shadow shadow-lg p-2 "> -->
                                                    <div class="col-3 d-flex align-items-center justify-content-center">
                                                        <img src="{{ asset('assets/dashboard/img/pdf.png') }}"
                                                            alt="" width="70px">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="mb-3 mt-2">
                                                            <input type="text" class="form-control nama_file_update"
                                                                id="nama_file" name="nama_file_update[]"
                                                                placeholder="Masukkan Nama File"
                                                                value="{{ $dokumen->nama_dokumen }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input name="file_dokumen_update[]"
                                                                class="form-control file_dokumen_update" type="file"
                                                                multiple="true" data-id="{{ $dokumen->id }}">
                                                            <span class="text-mute" style="font-size: 11px">Kosongkan
                                                                Dokumen Jika Tidak Ingin
                                                                Merubah Dokumen</span>
                                                            <p class="text-danger error-text nama_file_update-error my-0">
                                                            </p>
                                                            <p
                                                                class="text-danger error-text file_dokumen_update-error my-0">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="btn btn-danger fw-bold card-footer bg-danger text-center p-0"
                                                onclick="hapusUpdate({{ $loop->iteration }},{{ $dokumen->id }})"><i
                                                    class="fas fa-trash-alt"></i>
                                                Hapus</button>
                                        </div>
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
        var totalList = {{ count($sppTu->dokumenSppTu) }};
        var arrayNamaFileUpdate = {{ json_encode($sppTu->dokumenSppTu->pluck('id')->toArray()) }};
        var perbaiki = "{{ $request->perbaiki }}";
        var arrayDokumenHapus = [];
        var arrayDokumenUpdate = [];

        $(document).ready(function() {
            $('#spp-tu').addClass('active');
        })

        function hapus(id) {
            $('#box-upload-' + id).fadeOut("slow", function() {
                $("#box-upload-" + id).remove();
            });

        }

        function hapusUpdate(id, value) {
            $('#box-upload-' + id).fadeOut("slow", function() {
                $("#box-upload-" + id).remove();
            });
            arrayNamaFileUpdate = arrayNamaFileUpdate.filter(item => item !== value);
            arrayDokumenHapus.push(value);
        }

        $('#card-tambah').click(function() {
            totalList++;
            var cardUpload =
                '<div class="card box-upload" id="box-upload-' + totalList +
                '" style="display: none;"><div class="card-body"><div class="row"><div class="col-3 d-flex align-items-center justify-content-center"><img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt=""width="70px"></div><div class="col-9"><div class="mb-3 mt-2"><input type="text" class="form-control nama_file" id="nama_file" name="nama_file[]" placeholder="Masukkan Nama File" value=""></div><div class="mb-3"><input name="file_dokumen[]" class="form-control file_dokumen" type="file" id="formFile"  multiple="true"><p class="text-danger error-text nama_file-error my-0"></p><p class="text-danger error-text file_dokumen-error my-0"></p></div></div></div></div><button type="button" class="btn btn-danger fw-bold card-footer bg-danger text-center p-0" onclick="hapus(' +
                totalList + ')"><i class="fas fa-trash-alt"></i> Hapus</button>';
            $('#list-upload').append(cardUpload);
            $('#box-upload-' + totalList).fadeIn("slow");
        })

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            var totalDokumenKosong = 0;
            var totalNamaKosong = 0;
            var indexArray = 0;
            var totalDokumen = 0;
            var totalDokumenUpdate = 0;
            arrayDokumenUpdate = [];

            $('.surat-penolakan_error').html('');

            $(".file_dokumen_update-error").each(function() {
                $(this).html('');
            })

            $(".nama_file_update-error").each(function() {
                $(this).html('');
            })

            $(".file_dokumen-error").each(function() {
                $(this).html('');
            })

            $(".nama_file-error").each(function() {
                $(this).html('');
            })

            totalDokumen = $('.file_dokumen').length;
            totalDokumenUpdate = $('.file_dokumen_update').length;

            if (totalDokumen == 0 && totalDokumenUpdate == 0) {
                swal("Dokumen Kosong, Silahkan Tambahkan Dokumen Minimal 1", {
                    buttons: false,
                    timer: 1500,
                    icon: "warning",
                });
                return false;
            }

            $(".file_dokumen").each(function() {
                if ($(this).val() == '') {
                    $('.file_dokumen-error')[indexArray].innerHTML = 'Dokumen Tidak Boleh Kosong';
                    totalDokumenKosong++;
                }

                if ($(".nama_file")[indexArray].value == '') {
                    $('.nama_file-error')[indexArray].innerHTML = 'Nama File Tidak Boleh Kosong';
                    totalNamaKosong++;
                }

                indexArray++;
            })

            $(".file_dokumen_update").each(function() {
                if ($(this).val() != '') {
                    arrayDokumenUpdate.push($(this).data('id'));
                }
            })

            if (totalDokumenKosong != 0 || totalNamaKosong != 0) {
                swal("Periksa Kembali Data Anda", {
                    buttons: false,
                    timer: 1500,
                    icon: "warning",
                });
                return false;
            }

            var formData = new FormData($(this)[0]);
            formData.append('arrayNamaFileUpdate', JSON.stringify(arrayNamaFileUpdate));
            formData.append('arrayDokumenHapus', JSON.stringify(arrayDokumenHapus));
            formData.append('arrayDokumenUpdate', JSON.stringify(arrayDokumenUpdate));
            formData.append('perbaiki', perbaiki);
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
                        url: "{{ url('spp-tu/' . $sppTu->id) }}",
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
                                        $(location).attr('href', "{{ url('spp-tu') }}");
                                    }, 2000);
                            } else {
                                swal("Periksa Kembali Data Anda", {
                                    buttons: false,
                                    timer: 1500,
                                    icon: "warning",
                                });
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
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

        function printErrorMsg(msg) {
            $.each(msg, function(keyError, valueError) {
                var totalError = valueError.length;
                var indexError = 0;
                $.each(valueError, function(key, value) {
                    if (keyError.split(".").length > 1) {
                        $('.' + keyError.split(".")[0] + '-error')[keyError.split(".")[1]].innerHTML = $(
                            '.' +
                            keyError.split(".")[0] + '-error')[keyError.split(".")[1]].innerHTML + value;
                        if ((indexError + 1) != totalError) {
                            $('.' + keyError.split(".")[0] + '-error')[keyError.split(".")[1]].innerHTML =
                                $(
                                    '.' +
                                    keyError.split(".")[0] + '-error')[keyError.split(".")[1]].innerHTML +
                                ", ";
                        }
                    } else {
                        $('.' + keyError + '-error').text(value);
                    }
                    indexError++;
                });
            });
        }

        function resetError() {
            resetErrorElement('nama');
        }

        function resetModal() {
            resetError();
            $('#form-tambah')[0].reset();
        }

        // function printErrorMsg(msg) {
        //     $('.jawaban-error').each(function(i, o) {
        //         $(o).text('');
        //     })
        //     var i = 0;
        //     $.each(msg, function(key, value) {
        //         $('.text-jawaban').each(function(i, o) {
        //             if ($(o).val() == '') {
        //                 $('.jawaban-error').eq(i).text(value);
        //             };
        //         })
        //     });
        // }
    </script>
@endpush
