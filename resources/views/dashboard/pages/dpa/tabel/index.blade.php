@extends('dashboard.layouts.main')

@section('title')
    Dokumen Pelaksana Anggaran
@endsection

@push('style')
    <style>
        .table-bordered {
            border: 1px solid black !important;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid black !important;
            font-size: 13px !important;
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
            <a href="#">Dokumen Pelaksana Anggaran</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tabel</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Dokumen Pelaksana Anggaran</div>
                        <div class="card-tools">
                            @if (Auth::user()->role == 'Admin')
                                @component('dashboard.components.buttons.import',
                                    [
                                        'id' => 'btn-import',
                                        'class' => '',
                                        'label' => 'Import DPA',
                                    ])
                                @endcomponent

                                @component('dashboard.components.buttons.add',
                                    [
                                        'id' => 'btn-tambah',
                                        'class' => '',
                                        'url' => '#',
                                        'label' => 'Tambah DPA',
                                    ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('/tabel-dpa/export') }}" method="POST" enctype="multipart/form-data">
                        <div class="row align-items-end mb-4">
                            @csrf
                            @if (!in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']))
                                <div class="col">
                                    @component('dashboard.components.formElements.select',
                                        [
                                            'label' => 'Sekretariat Daerah',
                                            'id' => 'biro_organisasi',
                                            'class' => 'select2',
                                            'name' => 'biro_organisasi',
                                            'wajib' => '<sup class="text-danger">*</sup>',
                                        ])
                                        @slot('options')
                                            <option value="Semua">Semua</option>
                                            @foreach ($biroOrganisasi as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                </div>
                            @endif

                            <div class="col">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Tahun',
                                        'id' => 'tahun_filter',
                                        'name' => 'tahun',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @slot('options')
                                        @foreach ($tahun as $item)
                                            <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-2">
                                @component('dashboard.components.buttons.submit',
                                    [
                                        'label' => 'Export',
                                        'class' => 'col-12',
                                    ])
                                @endcomponent
                            </div>
                        </div>
                    </form>
                    <div id="tabel-spd">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" role="dialog" id="modal-import">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import DPA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <ol class="pl-3">
                            <li>Download Format Excel Berikut dan Sesuaikan Data DPA Sesuai Format Excel yang
                                Didownload
                                <br>
                                <a href="{{ url('tabel-dpa/format-import') }}" class="btn btn-sm btn-primary mt-2"><i
                                        class="fas fa-file-excel"></i> Format Import
                                    Excel</a>
                            </li>
                            <li class="mt-2">Pilih Tahun DPA</li>
                            @component('dashboard.components.formElements.select',
                                [
                                    'id' => 'tahun',
                                    'name' => 'tahun',
                                    'label' => 'Tahun DPA',
                                    'class' => 'select2',
                                    'options' => $tahun,
                                    // 'attribute' => 'required',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                @slot('options')
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                    @endforeach
                                @endslot
                            @endcomponent
                            <li class="mt-2">Pilih File Excel yang Didalamnya sudah terdapat file DPA yang sudah
                                disesuaikan dengan format
                                yang diberikan</li>
                            @component('dashboard.components.formElements.input',
                                [
                                    'id' => 'file_spd',
                                    'name' => 'file_spd',
                                    'type' => 'file',
                                    'label' => 'File DPA',
                                    'class' => 'form-control',
                                    // 'attribute' => 'required',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                            @endcomponent
                        </ol>
                        {{-- <label for=""></label>
                    <button class="btn btn-success">Format Import Excel</button> --}}
                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit',
                            [
                                'id' => 'btn-submit',
                                'label' => 'Import DPA',
                            ])
                        @endcomponent
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-tambah">
        <form method="POST" id="form-tambah">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-tambah-title">Tambah Dokumen Pelaksana Anggaran (DPA)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Tahun',
                                        'id' => 'tahun_tambah',
                                        'name' => 'tahun',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @slot('options')
                                        @foreach ($tahun as $item)
                                            <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-12">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Sekretariat Daerah',
                                        'id' => 'biro_organisasi_tambah',
                                        'name' => 'biro_organisasi',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @slot('options')
                                        @foreach ($biroOrganisasi as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                            </option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-12">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Program',
                                        'id' => 'program',
                                        'name' => 'program',
                                        'class' => 'select2 program',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-12">
                                @component('dashboard.components.formElements.select',
                                    [
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
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Jumlah Anggaran',
                                        'type' => 'text',
                                        'id' => 'jumlah_anggaran',
                                        'name' => 'jumlah_anggaran',
                                        'class' => 'uang',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Jumlah Anggaran',
                                    ])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit',
                            [
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
        var tipe = '';

        function resetFormTambah() {
            $('#form-tambah').trigger("reset");
            $('#form-tambah').find('.select2').val('').trigger('change');
        }

        $('#btn-tambah').click(function() {
            aksiTambah = 'tambah';
            tipe = 'tambah';
            idKegiatan = '';
            resetFormTambah();
            $('#modal-tambah').modal('show');
            $('#modal-tambah-title').html('Tambah Dokumen Pelaksana Anggaran');
        })



        $('#btn-import').click(function() {
            $('#modal-import').modal('show');
        });

        $('#form-import').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

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
                        url: "{{ url('tabel-dpa/import') }}",
                        type: 'POST',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-import').modal('hide');
                                swal("Berhasil", "Data Berhasil Di import", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                                window.location.replace("{{ url('/tabel-dpa') }}");
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
                }
            });
        })

        $('#form-tambah').submit(function(e) {
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
                    if (aksiTambah == 'tambah') {
                        $.ajax({
                            url: "{{ url('/tabel-dpa') }}",
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.status == 'success') {
                                    $('#modal-tambah').modal('hide');
                                    tabelSpd();
                                    swal("Berhasil", "Data Berhasil Tersimpan", {
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000,
                                    });
                                } else if (response.status == 'unique') {
                                    swal("Gagal", "Data DPA Sudah Ada", {
                                        icon: "error",
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
                    } else {
                        $.ajax({
                            url: "{{ url('/tabel-dpa') }}" + '/' + idEdit,
                            type: 'PUT',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.status == 'success') {
                                    $('#modal-tambah').modal('hide');
                                    tabelSpd();
                                    swal("Berhasil", "Data Berhasil Diubah", {
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000,
                                    });
                                } else if (response.status == 'unique') {
                                    swal("Gagal", "Data DPA Sudah Ada", {
                                        icon: "error",
                                        buttons: false,
                                        timer: 1000,
                                    });
                                } else {
                                    printErrorMsg(response.error);
                                }
                            },
                            error: function(response) {
                                swal("Gagal", "Data Gagal Diubah", {
                                    icon: "error",
                                    buttons: false,
                                    timer: 1000,
                                });
                            }
                        })
                    }
                }
            });


        })
    </script>

    <script>
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').removeClass('d-none');
                $('.' + key + '-error').text(value);
            });
        }

        $(document).ready(function() {
            $('#tabel-dpa').addClass('active');
            getProgram('tambah', '');
            tabelSpd();
        })

        $('#biro_organisasi').on('change', function() {
            tabelSpd();
        })

        $('#tahun_filter').on('change', function() {
            tabelSpd();
        })

        function tabelSpd() {
            var tahun = $('#tahun_filter').val();
            var biroOrganisasi = $('#biro_organisasi').val();
            $.ajax({
                url: "{{ url('tabel-dpa/tabel-dpa') }}",
                type: 'POST',
                data: {
                    'tahun': tahun,
                    'biro_organisasi': biroOrganisasi,
                },
                success: function(response) {
                    $('#tabel-spd').html(response);
                }
            })
        }
    </script>

    <script>
        $(document).on('click', '#btn-delete', function() {
            let id = $(this).val();
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'error',
                text: "Data yang sudah dihapus tidak dapat dikembalikan lagi !",
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Hapus',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    $.ajax({
                        url: "{{ url('tabel-dpa') }}" + '/' + id,
                        type: 'DELETE',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                swal("Berhasil", "Data Berhasil Dihapus", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                }).then(function() {
                                    tabelSpd();
                                })
                            } else {
                                swal("Gagal", "Data Gagal Dihapus", {
                                    icon: "error",
                                    buttons: false,
                                    timer: 1000,
                                });
                            }
                        }
                    })
                }
            });
        })

        $('#program').on('change', function() {
            getKegiatan('');
        })

        function getKegiatan() {
            var program = $('#program').val();
            $.ajax({
                url: "{{ url('list/kegiatan') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    tipe: tipe,
                    program: program,
                    id: idKegiatan,
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
        }

        $(document).on('click', '#btn-edit', function() {
            let id = $(this).val();
            idEdit = id;
            $('#form-tambah').trigger("reset");
            $.ajax({
                url: "{{ url('tabel-dpa') }}" + '/' + id + '/edit',
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    aksiTambah = 'ubah';
                    tipe = 'ubah';
                    idKegiatan = response.kegiatan.id;
                    resetFormTambah();
                    $('#modal-tambah-title').html('Ubah Dokumen Pelaksana Anggaran');
                    $('#biro_organisasi_tambah').val(response.biro_organisasi_id).trigger('change');
                    $('#tahun_tambah').val(response.tahun_id).trigger('change');
                    $('#jumlah_anggaran').val(response.jumlah_anggaran).trigger("input");
                    getProgram(tipe, response.kegiatan.program_id);
                    setTimeout(function() {
                        $('#program').val(response.kegiatan.program_id).trigger('change');
                    }, 1000);
                    setTimeout(function() {
                        $('#kegiatan').val(response.kegiatan_id).trigger('change');
                    }, 1500);
                    $('#modal-tambah').modal('show');
                },
            })
        })

        function getProgram(tipe, id) {
            $.ajax({
                url: "{{ url('list/program') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'tipe': tipe,
                    'id': id
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
    </script>
@endpush
