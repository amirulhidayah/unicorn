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
                                @component('dashboard.components.buttons.import', [
                                    'id' => 'btn-import',
                                    'class' => '',
                                    'label' => 'Import DPA',
                                ])
                                @endcomponent
                                <button class="btn btn-primary" id="btn-export"> <i class="fas fa-file-export"></i> Export
                                    DPA</button>

                                @component('dashboard.components.buttons.add', [
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
                    @if (count($errors) > 0)
                        <div class="alert alert-danger" role="alert">
                            Pastikan anda memilih seluruh filter
                        </div>
                    @endif
                    <form id="form-export" action="{{ url('/tabel-dpa/export') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="row align-items-end mb-4">
                            @csrf
                            <div class="col-md-12 col-sm-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Jenis SPP',
                                    'id' => 'jenis_spp_filter',
                                    'name' => 'jenis_spp_filter',
                                    'class' => 'select2 filter',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        <option value="SPP-GU">SPP-GU</option>
                                        <option value="SPP-LS">SPP-LS</option>
                                    @endslot
                                @endcomponent
                            </div>
                            @if (
                                !in_array(Auth::user()->role, [
                                    'Bendahara Pengeluaran',
                                    'Bendahara Pengeluaran Pembantu',
                                    'Bendahara Pengeluaran Pembantu Belanja Hibah',
                                ]))
                                <div class="col-md-6 col-sm-12">
                                    @component('dashboard.components.formElements.select', [
                                        'label' => 'Sekretariat Daerah',
                                        'id' => 'sekretariat_daerah',
                                        'class' => 'select2 filter',
                                        'name' => 'sekretariat_daerah',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                        @slot('options')
                                            <option value="Semua">Semua</option>
                                            @foreach ($sekretariatDaerah as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                </div>
                            @endif

                            <div
                                class="{{ in_array(Auth::user()->role, [
                                    'Bendahara Pengeluaran',
                                    'Bendahara Pengeluaran Pembantu',
                                    'Bendahara Pengeluaran Pembantu Belanja Hibah',
                                ])
                                    ? 'col-12'
                                    : 'col-6' }}">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Tahun',
                                    'id' => 'tahun_filter',
                                    'name' => 'tahun_filter',
                                    'class' => 'select2 filter',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        @foreach ($tahun as $item)
                                            <option value="{{ $item->id }}">{{ $item->tahun }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-md-6 col-sm-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Bulan Dari',
                                    'id' => 'bulan_dari_filter',
                                    'name' => 'bulan_dari_filter',
                                    'class' => 'select2 filter',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        @foreach ($daftarBulan as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-md-6 col-sm-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Bulan Sampai',
                                    'id' => 'bulan_sampai_filter',
                                    'name' => 'bulan_sampai_filter',
                                    'class' => 'select2 filter',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        @foreach ($daftarBulan as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    @endslot
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
                            @component('dashboard.components.formElements.select', [
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
                            @component('dashboard.components.formElements.input', [
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
                        @component('dashboard.components.buttons.submit', [
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
                                @component('dashboard.components.formElements.select', [
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
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Sekretariat Daerah',
                                    'id' => 'sekretariat_daerah_tambah',
                                    'name' => 'sekretariat_daerah',
                                    'class' => 'select2',
                                    'wajib' => '<sup class="text-danger">*</sup>',
                                ])
                                    @slot('options')
                                        @foreach ($sekretariatDaerah as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                            </option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-12">
                                @component('dashboard.components.formElements.select', [
                                    'label' => 'Program',
                                    'id' => 'program',
                                    'name' => 'program',
                                    'class' => 'select2 program',
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
                                @component('dashboard.components.formElements.input', [
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
        var tipe = '';
        var role = "{{ Auth::user()->role }}";
        var roleAdmin = ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'];
        var kegiatanEdit = null;
        var programEdit = null;

        function resetFormTambah() {
            $('#form-tambah').trigger("reset");
            $('#form-tambah').find('.select2').val('').trigger('change');
        }

        $('#btn-tambah').click(function() {
            aksiTambah = 'tambah';
            tipe = 'tambah';
            kegiatanEdit = null;
            programEdit = null;
            resetFormTambah();
            $('#modal-tambah').modal('show');
            $('#modal-tambah-title').html('Tambah Dokumen Pelaksana Anggaran');
        })

        $('#btn-export').click(function() {
            var tahun = $('#tahun_filter').val();
            var sekretariatDaerah = roleAdmin.includes(role) ? $('#sekretariat_daerah').val() :
                "{{ Auth::user()->profil->sekretariat_daerah_id }}";
            var bulanDari = $('#bulan_dari_filter').val();
            var bulanSampai = $('#bulan_sampai_filter').val();
            var jenisSpp = $('#jenis_spp_filter').val();

            var daftarBulan = @json($daftarBulan);
            var monthToNumber = function(monthName) {
                return daftarBulan.indexOf(monthName) + 1;
            };

            var bulanDariNumber = monthToNumber(bulanDari);
            var bulanSampaiNumber = monthToNumber(bulanSampai);

            if ((tahun != '') && (sekretariatDaerah != '') && (
                    bulanDari != '') && (bulanSampai != '') && (jenisSpp != '')) {
                if (bulanDariNumber <= bulanSampaiNumber) {
                    $('#form-export').submit();
                    return
                }
            }

            swal("Periksa Kembali Data", "Pastikan anda memilih seluruh filter", {
                icon: "error",
                buttons: false,
                timer: 3000,
            });
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
        $(document).ready(function() {
            $('#tabel-dpa').addClass('active');
            getProgram('tambah', '');
            tabelSpd();
        })

        $('.filter').change(function() {
            tabelSpd();
        })

        function tabelSpd() {
            var tahun = $('#tahun_filter').val();
            var sekretariatDaerah = $('#sekretariat_daerah').val();
            var bulanDari = $('#bulan_dari_filter').val();
            var bulanSampai = $('#bulan_sampai_filter').val();
            var jenisSpp = $('#jenis_spp_filter').val();
            $.ajax({
                url: "{{ url('tabel-dpa/tabel-dpa') }}",
                type: 'POST',
                data: {
                    'tahun': tahun,
                    'sekretariat_daerah': sekretariatDaerah,
                    'bulan_dari': bulanDari,
                    'bulan_sampai': bulanSampai,
                    'jenis_spp': jenisSpp
                },
                success: function(response) {
                    $('#tabel-spd').html(response);
                },
                error: function(response) {
                    $('#tabel-spd').html('');
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
                        },
                        error: function(response) {
                            swal("Gagal", "Data Gagal Dihapus", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
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
                url: "{{ url('list/kegiatan-dpa') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    program: program,
                    id: kegiatanEdit,
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
                        if (kegiatanEdit) {
                            $('#kegiatan').val(kegiatanEdit).trigger('change');
                        }
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
                    kegiatanEdit = response.kegiatan_dpa.id;
                    programEdit = response.kegiatan_dpa.program_dpa_id;
                    resetFormTambah();

                    $('#modal-tambah-title').html('Ubah Dokumen Pelaksana Anggaran');
                    $('#sekretariat_daerah_tambah').val(response.sekretariat_daerah_id).trigger(
                        'change');
                    $('#tahun_tambah').val(response.tahun_id).trigger('change');
                    $('#jumlah_anggaran').val(response.jumlah_anggaran).trigger("input");

                    getProgram();
                    getKegiatan();

                    $('#modal-tambah').modal('show');
                },
            })
        })

        function getProgram() {
            $.ajax({
                url: "{{ url('list/program-dpa') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': programEdit,
                },
                success: function(response) {
                    if (response.length > 0) {
                        $('.program').html('');
                        $('.program').append('<option value="">Pilih Program</option>');
                        $.each(response, function(key, value) {
                            $('.program').append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                        if (programEdit) {
                            $('.program').val(programEdit).trigger('change');
                        }
                    } else {
                        $('.program').html('');
                    }
                }
            })
        }
    </script>
@endpush
