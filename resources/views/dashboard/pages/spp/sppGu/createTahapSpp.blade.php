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
                                    'isi' => '<span class="badge badge-primary">SPP</span>',
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
                                @component('dashboard.components.widgets.info', [
                                    'judul' => 'Anggaran Yang Direncanakan',
                                    'isi' => $perencanaanAnggaran,
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
                                <small class="text-danger error-text dokumenFileHitung-error"
                                    id="dokumenFileHitung-error"></small>
                                <div id="list-upload">
                                    @forelse ($daftarDokumenSppGu as $dokumen)
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
        var jumlahAnggaran = {{ $perencanaanAnggaranHitung }};

        $(document).ready(function() {
            $('#spp-gu').addClass('active');
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
