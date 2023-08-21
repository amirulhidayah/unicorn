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
    <form method="POST" id="form-tambah" enctype="multipart/form-data" action="{{ url('spp-tu') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Tambah SPP TU</div>
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
                                        'label' => 'Bulan',
                                        'id' => 'bulan',
                                        'name' => 'bulan',
                                        'class' => 'select2',
                                        'attribute' => 'disabled',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                        @slot('options')
                                            <option value="Januari">Januari</option>
                                            <option value="Februari">Februari</option>
                                            <option value="Maret">Maret</option>
                                            <option value="April">April</option>
                                            <option value="Mei">Mei</option>
                                            <option value="Juni">Juni</option>
                                            <option value="Juli">Juli</option>
                                            <option value="Agustus">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="Oktober">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="Desember">Desember</option>
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
                                    <small class="text-danger error-text dokumenFileHitung-error"
                                        id="dokumenFileHitung-error"></small>
                                    @forelse ($daftarDokumenSppTu as $dokumen)
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'labelNama' => $dokumen->nama,
                                            'nameFileDokumen' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
                                            'classNama' => 'nama_file',
                                            'classDokumen' => 'file_dokumen',
                                        ])
                                        @endcomponent
                                    @empty
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'nameFileDokumen' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
                                            'classNama' => 'nama_file',
                                            'classDokumen' => 'file_dokumen',
                                        ])
                                        @endcomponent
                                    @endforelse
                                </div>

                                <div class="card bg-light border border-black" id="card-tambah">
                                    <div class="card-body text-dark  text-center">
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
        var totalList = "{{ count($daftarDokumenSppTu) == 0 ? 1 : count($daftarDokumenSppTu) }}";
        var idProgram = '';

        $(document).ready(function() {
            $('#spp-tu').addClass('active');
            getProgram();
        })

        function hapus(id) {
            $("#box-upload-" + id).remove();
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
                        url: "{{ url('spp-tu') }}",
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
                        getProgram();
                        swal("Berhasil", "Data Berhasil Tersimpan", {
                            icon: "success",
                            buttons: false,
                            timer: 1000,
                        });
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
                url: "{{ url('master-data/kegiatan/') }}" + "/" + idProgram,
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

        function getProgram() {
            $.ajax({
                url: "{{ url('list/program') }}",
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
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $('.program').html('');
                    }
                }
            })
        }

        $('#tahun').on('change', function() {
            var tahun = $(this).val();
            $('#bulan').attr('disabled', false);
        })

        $('#bulan').on('change', function() {
            $('#program').attr('disabled', false);
        })

        $('#program').on('change', function() {
            var program = $('#program').val();
            var tahun = $('#tahun').val();
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
                    $('#programSpp').val(program).select2().trigger('change');
                    idProgram = program;
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

        $('#programSpp').on('change', function() {
            idProgram = $(this).val();
        })

        $("#kegiatan").on('change', function() {
            $('#jumlah_anggaran').val('0').attr('disabled', false);
        })
    </script>
@endpush
