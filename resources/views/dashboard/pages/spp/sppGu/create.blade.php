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
                            <div class="card-title">Buat SPP GU</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @component('dashboard.components.formElements.input', [
                                    'label' => 'Nomor Surat Permintaan Pembayaran (SPP)',
                                    'type' => 'text',
                                    'id' => 'nomor_surat',
                                    'class' => '',
                                    'name' => 'nomor_surat',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                    'placeholder' => 'Masukkan Nomor Surat Permintaan Pembayaran (SPP)',
                                ])
                                @endcomponent
                            </div>
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
                                    <label class="form-label my-2 fw-bold">Sekretariat Daerah</label>
                                    <br>
                                    <label for="exampleFormControlInput1"
                                        class="badge badge-primary text-light mb-2">{{ Auth::user()->profil->SekretariatDaerah->nama }}</label>
                                    <br>
                                </div>
                            @endif
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
                            <div class="col-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Nomor Surat Pertanggungjawaban (SPJ)',
                                    'id' => 'spj_gu',
                                    'name' => 'spj_gu',
                                    'class' => 'select2',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                    @endslot
                                @endcomponent
                            </div>
                        </div>
                        <div class="row" id="append-spp-gu">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="TextInput" class="form-label mt-2 mb-3 fw-bold">Dokumen Pendukung<sup
                                        class="text-danger">*</sup></label>
                                <small class="text-danger error-text dokumenFileHitung-error"
                                    id="dokumenFileHitung-error"></small>
                                <div id="list-upload" class="row">
                                    @forelse ($daftarDokumenSppGu as $dokumen)
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'labelNama' => $dokumen->nama,
                                            'nameFileDokumen' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
                                            'class' => 'col-4',
                                            'classNama' => 'nama_file',
                                            'classDokumen' => 'file_dokumen',
                                        ])
                                        @endcomponent
                                    @empty
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'nameFileDokumen' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
                                            'classNama' => 'nama_file',
                                            'class' => 'col-4',
                                            'classDokumen' => 'file_dokumen',
                                        ])
                                        @endcomponent
                                    @endforelse
                                </div>

                                <div class="card bg-light border border-black" id="card-tambah">
                                    <div class="card-body text-dark text-center">
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
        var totalList = 1;
        var jumlahAnggaran = 0;
        let arrayJumlahAnggaran = [];

        $(document).ready(function() {
            $('#menu-spp-gu').collapse('show');
            $('#spp-gu').addClass('active');
            $('#spp-gu-spp').addClass('active');
            $('#btn-tambah-program-kegiatan').click();
            cekProgramKegiatan();
            hitungTotal();
        })

        function hapus(id) {
            $("#box-upload-" + id).remove();
        }

        $('#sekretariat_daerah').change(function() {
            getSpj();
            getSpp();
        })

        $('#spj_gu').change(function() {
            getSpj();
            getSpp();
        })

        function getSpp() {
            $.ajax({
                url: "{{ url('append/spp-gu') }}",
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#spj_gu').val()
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('#append-spp-gu').html(response.html);
                    } else {
                        $('#append-spp-gu').empty();
                    }
                },
                error: function(response) {
                    swal("Gagal", "Terjadi Kesalahan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                },
            })
        }

        function getSpj() {
            $.ajax({
                url: "{{ url('list/spjGu') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'sekretariat_daerah': $('#sekretariat_daerah').val(),
                    'tahun': $('#tahun').val(),
                },
                success: function(response) {
                    if (response.length > 0) {
                        $('#spj_gu').html('');
                        $('#spj_gu').append('<option value="">Nomor Surat Pertanggungjawaban (SPJ)</option>');
                        $.each(response, function(key, value) {
                            $('#spj_gu').append('<option value="' + value.id + '">' + value
                                .nomor_surat + '</option>');
                        })
                    } else {
                        $('#spj_gu').html('');
                    }
                },
                error: function(response) {
                    swal("Gagal", "Terjadi Kesalahan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                },
            })
        }

        $('#tahun').change(function() {
            getSpj();
            getSpp();
        })

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
            resetError();
            var formData = new FormData(this);

            $('.program').each(function() {
                var nama = $(this).attr('name');
                formData.append('program[]', nama);
            });

            $('.kegiatan').each(function() {
                var nama = $(this).attr('name');
                formData.append('kegiatan[]', nama);
            });

            $('.anggaran-digunakan').each(function() {
                var nama = $(this).attr('name');
                formData.append('anggaranDigunakan[]', nama);
            });

            $('.file_dokumen').each(function() {
                var nama = $(this).attr('name');
                formData.append('fileDokumen[]', nama);
            });

            $('.nama_file').each(function() {
                var nama = $(this).attr('name');
                formData.append('namaFile[]', nama);
            });

            formData.append('arrayJumlahAnggaran', JSON.stringify(arrayJumlahAnggaran));

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
                        url: "{{ url('spp-gu') }}",
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
    </script>
@endpush
