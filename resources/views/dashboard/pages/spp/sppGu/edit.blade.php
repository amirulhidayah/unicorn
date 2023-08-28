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
    <form id="form-tambah" enctype="multipart/form-data">
        {{ csrf_field() }}
        @method('PUT')
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
                            @if ($sppGu->alasan_validasi_asn != null)
                                <div class="col-6">
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'asn',
                                        'tanggal' => $sppGu->tanggal_validasi_asn,
                                        'isi' => $sppGu->alasan_validasi_asn,
                                    ])
                                    @endcomponent
                                </div>
                            @endif

                            @if ($sppGu->alasan_validasi_ppk != null)
                                <div class="col-6">
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'ppk',
                                        'tanggal' => $sppGu->tanggal_validasi_ppk,
                                        'isi' => $sppGu->alasan_validasi_ppk,
                                    ])
                                    @endcomponent
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
                                    'value' => $sppGu->nomor_surat,
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
                                            @foreach ($daftarSekretariatDaerah as $sekretariatDaerah)
                                                <option value="{{ $sekretariatDaerah->id }}"
                                                    {{ $sekretariatDaerah->id == $sppGu->spjGu->sekretariat_daerah_id ? 'selected' : '' }}>
                                                    {{ $sekretariatDaerah->nama }}
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
                                        class="badge badge-primary text-light mb-2">{{ Auth::user()->profil->sekretariatDaerah->nama }}</label>
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
                                            <option value="{{ $tahun->id }}"
                                                {{ $tahun->id == $sppGu->spjGu->tahun_id ? 'selected' : '' }}>{{ $tahun->tahun }}
                                            </option>
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
                                @if ($sppGu->status_validasi_ppk == 2 || $sppGu->status_validasi_asn == 2)
                                    <div class="row">
                                        <div class="col-12">
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
                                                                <p class="fw-bold" style="font-size: 15px;">Surat
                                                                    Pengembalian
                                                                </p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input name="surat_pengembalian" class="form-control"
                                                                    type="file" accept="application/pdf">
                                                                <p
                                                                    class="text-danger error-text surat_pengembalian-error my-0">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fw-bold card-footer bg-primary text-light text-center p-0">
                                                    ! Wajib Dimasukan</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div id="list-upload" class="row">
                                    @foreach ($sppGu->dokumenSppGu as $dokumen)
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'labelNama' => $dokumen->nama_dokumen,
                                            'nameFileDokumen' => $dokumen->id,
                                            'classDokumen' => 'file_dokumen_update',
                                            'class' => 'col-4',
                                            'classNama' => 'nama_file_update',
                                        ])
                                        @endcomponent
                                    @endforeach
                                </div>
                                <div class="card bg-light border border-black" id="card-tambah">
                                    <div class="card-body text-dark text-center">
                                        <i class="fas fa-plus-circle fa-2xl" style="font-size: 75px"></i>
                                        <p class="my-0 fw-bold">Tambah Dokumen</p>
                                    </div>
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
        const spjGuId = "{{ $sppGu->spj_gu_id }}";

        $(document).ready(function() {
            $('#menu-spp-gu').collapse('show');
            $('#spp-gu').addClass('active');
            $('#spp-gu-spp').addClass('active');
            $('#btn-tambah-program-kegiatan').click();
            $('#tahun').change();
        })

        function hapus(id) {
            $("#box-upload-" + id).remove();
        }

        $('#sekretariat_daerah').change(function() {
            getSpj();
            getSpp();
        })

        $('#spj_gu').change(function() {
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
                    'id': spjGuId
                },
                success: function(response) {
                    if (response.length > 0) {
                        $('#spj_gu').html('');
                        $('#spj_gu').append('<option value="">Nomor Surat Pertanggungjawaban (SPJ)</option>');
                        $.each(response, function(key, value) {
                            $('#spj_gu').append('<option value="' + value.id + '">' + value
                                .nomor_surat + '</option>');
                        })
                        if (spjGuId) {
                            $('#spj_gu').val(spjGuId).trigger('change');
                        }
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
