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
    <form method="POST" id="form-tambah" enctype="multipart/form-data" action="{{ url('spp-up') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Tambah SPP UP</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if (Auth::user()->role == 'Admin')
                                    <div class="col-12">
                                        @component('dashboard.components.formElements.select', [
                                            'label' => 'Sekretariat Daerah',
                                            'id' => 'sekretariat_daerah',
                                            'name' => 'sekretariat_daerah',
                                            'class' => 'select2',
                                            'wajib' => '<sup class="text-danger">*</sup>',
                                        ])
                                            @slot('options')
                                                @foreach ($daftarSekretariatDaerah as $SekretariatDaerah)
                                                    <option value="{{ $SekretariatDaerah->id }}">{{ $SekretariatDaerah->nama }}
                                                    </option>
                                                @endforeach
                                            @endslot
                                        @endcomponent
                                    </div>
                                @else
                                    <div class="col-12">
                                        <label for="exampleFormControlInput1">Sekretariat Daerah</label>
                                        <br>
                                        <label for="exampleFormControlInput1"
                                            class="badge badge-primary text-light my-2">{{ Auth::user()->profil->SekretariatDaerah->nama }}</label>
                                        <br>
                                    </div>
                                @endif

                                <div class="col-12">
                                    @component('dashboard.components.formElements.input', [
                                        'label' => 'Nomor Surat',
                                        'type' => 'text',
                                        'id' => 'nomor_surat',
                                        'class' => '',
                                        'name' => 'nomor_surat',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Nomor Surat',
                                    ])
                                    @endcomponent
                                </div>

                                <div class="col-12">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Tahun',
                                        'id' => 'tahun',
                                        'name' => 'tahun',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                        @slot('options')
                                            @foreach ($daftarTahun as $tahun)
                                                <option value="{{ $tahun->id }}">{{ $tahun->tahun }}</option>
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                </div>

                                <div class="col-12 my-2">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Program',
                                        'id' => 'program',
                                        'name' => 'program',
                                        'class' => 'select2 program',
                                        'attribute' => 'disabled',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'btnId' => 'tambahProgram',
                                    ])
                                    @endcomponent
                                </div>

                                <div class="col-12 my-2">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Kegiatan',
                                        'id' => 'kegiatan',
                                        'name' => 'kegiatan',
                                        'class' => 'select2',
                                        'attribute' => 'disabled',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'btnId' => 'tambahKegiatan',
                                    ])
                                    @endcomponent
                                </div>
                                <div class="col-12">
                                    @component('dashboard.components.formElements.input', [
                                        'label' => 'Jumlah Anggaran (Rp)',
                                        'type' => 'text',
                                        'id' => 'jumlah_anggaran',
                                        'class' => 'uang',
                                        'name' => 'jumlah_anggaran',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'attribute' => 'disabled',
                                        'value' => '0',
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
                                    @forelse ($daftarDokumenSppUp as $dokumen)
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
                                                                placeholder="Masukkan Nama File" value="">
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

    <div class="modal" tabindex="-1" role="dialog" id="modal-tambah-program">
        <form method="POST" id="form-tambah-program">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-tambah-title">Tambah Program SPP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.input', [
                                    'id' => 'nama',
                                    'type' => 'text',
                                    'label' => 'Nama Daftar Program SPP',
                                    'placeholder' => 'Tambah Program SPP',
                                    'name' => 'nama',
                                    'required' => true,
                                ])
                                @endcomponent
                            </div>
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.input', [
                                    'id' => 'no_rek',
                                    'type' => 'text',
                                    'label' => 'No. Rekening',
                                    'placeholder' => 'Tambah Nomor Rekening',
                                    'class' => 'numerik',
                                    'name' => 'no_rek',
                                    'required' => true,
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                @endcomponent
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit', [
                            'label' => 'Simpan',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" role="dialog" id="modal-tambah-kegiatan">
        <form method="POST" id="form-tambah-kegiatan">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-tambah-title">Tambah Kegiatan SPP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Program',
                                    'id' => 'programSpp',
                                    'name' => 'program',
                                    'class' => 'select2 program',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                @endcomponent
                            </div>
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.input', [
                                    'id' => 'nama',
                                    'type' => 'text',
                                    'label' => 'Nama Daftar Kegiatan SPP',
                                    'placeholder' => 'Tambah Kegiatan SPP',
                                    'name' => 'nama',
                                    'required' => true,
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                @endcomponent
                            </div>
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.input', [
                                    'id' => 'no_rek',
                                    'type' => 'text',
                                    'label' => 'No. Rekening',
                                    'placeholder' => 'Tambah Nomor Rekening',
                                    'class' => 'numerik',
                                    'name' => 'no_rek',
                                    'required' => true,
                                ])
                                @endcomponent
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit', [
                            'label' => 'Simpan',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        var totalList = "{{ count($daftarDokumenSppUp) == 0 ? 1 : count($daftarDokumenSppUp) }}";
        var idProgram = '';
        console.log(totalList);

        $(document).ready(function() {
            $('#spp-up').addClass('active');
            getProgramSpp();
        })

        function hapus(id) {
            $('#box-upload-' + id).fadeOut("slow", function() {
                $("#box-upload-" + id).remove();
            });
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
                        url: "{{ url('spp-up') }}",
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
                                        $(location).attr('href', "{{ url('spp-up') }}");
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

        $('#tambahProgram').click(function() {
            $('#modal-tambah-program').modal('show');
            $('#form-tambah-program')[0].reset();
        })

        $('#tambahKegiatan').click(function() {
            $('#modal-tambah-kegiatan').modal('show');
        })

        $('#form-tambah-program').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('master-data/program-spp') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modal-tambah-program').modal('hide');
                        getProgramSpp();
                        swal("Berhasil", "Data Berhasil Tersimpan", {
                            icon: "success",
                            buttons: false,
                            timer: 1000,
                        });
                    } else {
                        printErrorMsg(response.error);
                    }
                },
                error: function(response) {
                    swal("Gagal", "Data Gagal Ditambahkan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                }
            })
        })

        $('#form-tambah-kegiatan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('master-data/kegiatan-spp/') }}" + "/" + idProgram,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        $('#program').val('').trigger('change');
                        $('#kegiatan').val('').trigger('change');
                        $('#modal-tambah-kegiatan').modal('hide');
                        swal("Berhasil", "Data Berhasil Tersimpan", {
                            icon: "success",
                            buttons: false,
                            timer: 1000,
                        });
                    } else {
                        printErrorMsg(response.error);
                    }
                },
                error: function(response) {
                    swal("Gagal", "Data Gagal Ditambahkan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                }
            })
        })

        function getProgramSpp() {
            $.ajax({
                url: "{{ url('list/program-spp') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.length > 0) {
                        $('.program').html('');
                        $('.program').append('<option value="">Pilih Program</option>');
                        $.each(response, function(key, value) {
                            $('.program').append('<option value="' + value.id + '">' + value
                                .nama + '</option>');
                        })
                    } else {
                        $('.program').html('');
                    }
                }
            })
        }

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
            $('#program').attr('disabled', false);
        })

        $('#program').on('change', function() {
            var program = $('#program').val();
            var tahun = $('#tahun').val();
            $.ajax({
                url: "{{ url('list/kegiatan-spp') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    program: program
                },
                success: function(response) {
                    $('#kegiatan').removeAttr('disabled');
                    $('#programSpp').val(program).select2().trigger('change');
                    idProgram = program;
                    if (response.length > 0) {
                        $('#kegiatan').html('');
                        $('#kegiatan').append('<option value="">Pilih kegiatan</option>');
                        $.each(response, function(key, value) {
                            $('#kegiatan').append('<option value="' + value.id + '">' + value
                                .nama + '</option>');
                        })
                    } else {
                        $('#kegiatan').html('');
                    }
                }
            })
        })

        $('#programSpp').on('change', function() {
            idProgram = $(this).val();
        })

        $("#kegiatan").on('change', function() {
            $('#jumlah_anggaran').val('0').attr('disabled', false);
        })
    </script>
@endpush
