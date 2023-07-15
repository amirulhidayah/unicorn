@extends('dashboard.layouts.main')

@section('title')
    SPP GU
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
            <a href="#">SPP GU</a>
        </li>
    </ul>
@endsection

@section('content')
    <form method="POST" id="form-tambah" enctype="multipart/form-data" action="{{ url('spp-gu') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Tambah SPP GU</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Tahap',
                                    'isi' => '<span class="badge badge-success">Akhir</span>',
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
                                <div class="col-12">
                                    @component('dashboard.components.formElements.input', [
                                        'label' => 'Anggaran Yang Digunakan (Rp)',
                                        'type' => 'text',
                                        'id' => 'anggaran_digunakan',
                                        'name' => 'anggaran_digunakan',
                                        'class' => 'uang',
                                        'value' => $sppGu->anggaran_digunakan,
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Anggaran Yang Digunakan',
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                    @forelse ($daftarDokumenSppGu as $dokumen)
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
                                                            <input type="text" class="form-control nama_file"
                                                                id="nama_file" name="nama_file[]"
                                                                placeholder="Masukkan Nama File"
                                                                value="{{ $dokumen->nama }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input name="file_dokumen[]" class="form-control file_dokumen"
                                                                type="file" multiple="true">
                                                            <p class="text-danger error-text nama_file-error my-0"></p>
                                                            <p class="text-danger error-text file_dokumen-error my-0"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="btn btn-danger fw-bold card-footer bg-danger text-center p-0"
                                                onclick="hapus({{ $loop->iteration }})"><i class="fas fa-trash-alt"></i>
                                                Hapus</button>
                                        </div>
                                    @empty
                                        <div class="card box-upload" id="box-upload-1" class="box-upload">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- <div class="d-flex border rounded shadow shadow-lg p-2 "> -->
                                                    <div class="col-3 d-flex align-items-center justify-content-center">
                                                        <img src="{{ asset('assets/dashboard/img/pdf.png') }}"
                                                            alt="" width="70px">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="mb-3 mt-2">
                                                            <input type="text" class="form-control nama_file"
                                                                id="nama_file" name="nama_file[]"
                                                                placeholder="Masukkan Nama File"
                                                                value="{{ $dokumen->nama }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input name="file_dokumen[]" class="form-control file_dokumen"
                                                                type="file" multiple="true">
                                                            <p class="text-danger error-text nama_file-error my-0"></p>
                                                            <p class="text-danger error-text file_dokumen-error my-0"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="btn btn-danger fw-bold card-footer bg-danger text-center p-0"
                                                onclick="hapus(1)"><i class="fas fa-trash-alt"></i>
                                                Hapus</button>
                                        </div>
                                    @endforelse
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
        var totalList = "{{ count($daftarDokumenSppGu) == 0 ? 1 : count($daftarDokumenSppGu) }}";
        var jumlahAnggaran = 0;

        $(document).ready(function() {
            $('#spp-gu').addClass('active');
        })

        function hapus(id) {
            $('#box-upload-' + id).fadeOut("slow", function() {
                $("#box-upload-" + id).remove();
            });
        }

        $('#card-tambah').click(function() {
            totalList++;
            var cardUpload = tambahCardUpload(totalList, '');
            $('#list-upload').append(cardUpload);
            $('#box-upload-' + totalList).fadeIn("slow");
        })

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            var totalDokumenKosong = 0;
            var totalNamaKosong = 0;
            var indexArray = 0;
            var totalDokumen = 0;

            $(".file_dokumen-error").each(function() {
                $(this).html('');
            })

            $(".nama_file-error").each(function() {
                $(this).html('');
            })

            totalDokumen = $('.file_dokumen').length;

            if (totalDokumen == 0) {
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

            if (totalDokumenKosong != 0 || totalNamaKosong != 0) {
                swal("Periksa Kembali Data Anda", {
                    buttons: false,
                    timer: 1500,
                    icon: "warning",
                });
                return false;
            }

            var formData = new FormData($(this)[0]);

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
                        url: "{{ url('spp-gu/' . $sppGu->id) }}",
                        type: "POST",
                        data: formData,
                        async: false,
                        success: function(response) {
                            if (response.status == "success") {
                                swal("Berhasil",
                                    "Dokumen berhasil ditambahkan", {
                                        button: false,
                                        icon: "success",
                                    });
                                setTimeout(
                                    function() {
                                        $(location).attr('href', "{{ url('spp-gu') }}");
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

        $('#tahun').on('change', function() {
            var tahun = $(this).val();
            $('#kegiatan').html('').attr('disabled', true);
            $('#tw').val('').trigger('change').attr('disabled', true);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
            $.ajax({
                url: "{{ url('list/program') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun
                },
                success: function(response) {
                    $('#program').removeAttr('disabled');
                    if (response.length > 0) {
                        $('#program').html('');
                        $('#program').append('<option value="">Pilih Program</option>');
                        $.each(response, function(key, value) {
                            $('#program').append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $('#program').html('');
                    }
                }
            })
        })

        $('#program').on('change', function() {
            var program = $('#program').val();
            var tahun = $('#tahun').val();
            $('#tw').val('').trigger('change').attr('disabled', true);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
            $.ajax({
                url: "{{ url('list/kegiatan') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    program: program
                },
                success: function(response) {
                    $('#kegiatan').removeAttr('disabled');
                    if (response.length > 0) {
                        $('#kegiatan').html('');
                        $('#kegiatan').append('<option value="">Pilih kegiatan</option>');
                        $.each(response, function(key, value) {
                            $('#kegiatan').append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $('#kegiatan').html('');
                    }
                }
            })
        })

        $("#kegiatan").on('change', function() {
            $('#tw').val('').trigger('change').attr('disabled', false);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
        })

        $('#tw').on('change', function() {
            $('#anggaran_digunakan').val('0').attr('disabled', false);
            var kegiatan = $('#kegiatan').val();
            var tahun = $('#tahun').val();
            var tw = $('#tw').val();
            $.ajax({
                url: "{{ url('tabel-dpa/get-spd') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    kegiatan: kegiatan,
                    tw: tw
                },
                success: function(response) {
                    jumlahAnggaran = response.jumlah_anggaran;
                    $('#jumlah_anggaran').val(formatRupiah(response.jumlah_anggaran));
                }
            })
        })

        $('#anggaran_digunakan').keyup(function() {
            var anggaranDigunakan = $('#anggaran_digunakan').val().split('.').join('');
            if (anggaranDigunakan > jumlahAnggaran) {
                $('.anggaran_digunakan-error').html('Anggaran melebihi anggaran yang telah ditentukan');
            } else {
                $('.anggaran_digunakan-error').html('');
            }
        })

        function tambahCardUpload(totalList, nama_file) {
            var cardUpload =
                '<div class="card box-upload" id="box-upload-' + totalList +
                '" style="display: none;"><div class="card-body"><div class="row"><div class="col-3 d-flex align-items-center justify-content-center"><img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt=""width="70px"></div><div class="col-9"><div class="mb-3 mt-2"><input type="text" class="form-control nama_file" id="nama_file" name="nama_file[]" placeholder="Masukkan Nama File" value="' +
                nama_file +
                '"></div><div class="mb-3"><input name="file_dokumen[]" class="form-control file_dokumen" type="file" id="formFile"  multiple="true"><p class="text-danger error-text nama_file-error my-0"></p><p class="text-danger error-text file_dokumen-error my-0"></p></div></div></div></div><button type="button" class="btn btn-danger fw-bold card-footer bg-danger text-center p-0" onclick="hapus(' +
                totalList + ')"><i class="fas fa-trash-alt"></i> Hapus</button>';
            return cardUpload;
        }
    </script>
@endpush
