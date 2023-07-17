@extends('dashboard.layouts.main')

@section('title')
    SPP LS
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
            <a href="#">SPP LS</a>
        </li>
    </ul>
@endsection

@section('content')
    <form method="POST" id="form-tambah" enctype="multipart/form-data" action="{{ url('spp-ls') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Tambah SPP LS</div>
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
                                        'label' => 'Kategori',
                                        'id' => 'kategori',
                                        'name' => 'kategori',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                        @slot('options')
                                            <option value="Belanja Hibah">Belanja Hibah</option>
                                            <option value="Barang dan Jasa">Barang dan Jasa</option>
                                        @endslot
                                    @endcomponent
                                </div>
                                <div class="col-12">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Tahun',
                                        'id' => 'tahun',
                                        'name' => 'tahun',
                                        'class' => 'select2',
                                        'attribute' => 'disabled',
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
                                        'label' => 'Program',
                                        'id' => 'program',
                                        'name' => 'program',
                                        'class' => 'select2',
                                        'attribute' => 'disabled',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @endcomponent
                                </div>

                                <div class="col-12">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Kegiatan',
                                        'id' => 'kegiatan',
                                        'name' => 'kegiatan',
                                        'class' => 'select2',
                                        'attribute' => 'disabled',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
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

                                <div class="col-12">
                                    @component('dashboard.components.formElements.input', [
                                        'label' => 'Anggaran Yang Digunakan (Rp)',
                                        'type' => 'text',
                                        'id' => 'anggaran_digunakan',
                                        'name' => 'anggaran_digunakan',
                                        'class' => 'uang',
                                        'attribute' => 'disabled',
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
                                <small class="text-danger error-text dokumenFileHitung-error"
                                    id="dokumenFileHitung-error"></small>
                                <div id="list-upload">
                                    @component('dashboard.components.dynamicForm.spp', [
                                        'nameFileDokumen' => substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10),
                                        'classNama' => 'nama_file',
                                        'classDokumen' => 'file_dokumen',
                                    ])
                                    @endcomponent
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
        var totalList = 1;
        var jumlahAnggaran = 0;

        $(document).ready(function() {
            $('#spp-ls').addClass('active');
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
                        url: "{{ url('spp-ls') }}",
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
                                        $(location).attr('href', "{{ url('spp-ls') }}");
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

        $('#kategori').on('change', function() {
            var kategori = $(this).val();
            $('#program').html('').attr('disabled', true);
            $('#kegiatan').html('').attr('disabled', true);
            $('#bulan').val('').trigger('change').attr('disabled', true);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
            $.ajax({
                url: "{{ url('append/sppLs') }}",
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}',
                    kategori: kategori
                },
                success: function(response) {
                    $('#tahun').removeAttr('disabled');
                    if (response.status == 'success') {
                        $('#list-upload').html('');
                        $('#list-upload').html(response.html);
                    } else {
                        $('#list-upload').html('');
                        $('#card-tambah').click();
                    }
                }
            })
        })

        $('#sekretariat_daerah').on('change', function() {
            $('#kategori').val('').trigger('change');
            $('#program').val('').trigger('change');
            $('#bulan').val('').trigger('change');
            $('#tahun').val('').trigger('change');
            $('#bulan').val('').trigger('change');
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0');
        })

        $('#tahun').on('change', function() {
            var tahun = $(this).val();
            var SekretariatDaerah = $('#sekretariat_daerah').val();
            $('#kegiatan').html('').attr('disabled', true);
            $('#bulan').val('').trigger('change').attr('disabled', true);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
            $.ajax({
                url: "{{ url('list/program-dpa') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    sekretariat_daerah: SekretariatDaerah
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
            var SekretariatDaerah = $('#sekretariat_daerah').val();
            $('#bulan').val('').trigger('change').attr('disabled', true);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
            $.ajax({
                url: "{{ url('list/kegiatan-dpa') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    program: program,
                    sekretariat_daerah: SekretariatDaerah
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
            $('#bulan').val('').trigger('change').attr('disabled', false);
            $('#jumlah_anggaran').val('0');
            $('#anggaran_digunakan').val('0').attr('disabled', true);
        })

        $('#bulan').on('change', function() {
            $('#anggaran_digunakan').val('0').attr('disabled', false);
            var kegiatan = $('#kegiatan').val();
            var tahun = $('#tahun').val();
            var bulan = $('#bulan').val();
            var SekretariatDaerah = $('#sekretariat_daerah').val();
            $.ajax({
                url: "{{ url('tabel-dpa/get-spd') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tahun: tahun,
                    kegiatan: kegiatan,
                    bulan: bulan,
                    sekretariat_daerah: SekretariatDaerah
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
    </script>
@endpush
