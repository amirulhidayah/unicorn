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
    <form id="form-tambah" enctype="multipart/form-data">
        {{ csrf_field() }}
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Edit SPP UP</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if ($sppUp->alasan_validasi_asn != null)
                                <div class="col-6">
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'asn',
                                        'tanggal' => $sppUp->tanggal_validasi_asn,
                                        'isi' => $sppUp->alasan_validasi_asn,
                                    ])
                                    @endcomponent
                                </div>
                            @endif

                            @if ($sppUp->alasan_validasi_ppk != null)
                                <div class="col-6">
                                    @component('dashboard.components.widgets.alert', [
                                        'oleh' => 'ppk',
                                        'tanggal' => $sppUp->tanggal_validasi_ppk,
                                        'isi' => $sppUp->alasan_validasi_ppk,
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
                                    'value' => $sppUp->nomor_surat,
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
                                                    {{ $sekretariatDaerah->id == $sppUp->sekretariat_daerah_id ? 'selected' : '' }}>
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
                                            <option value="{{ $tahun->id }}"
                                                {{ $tahun->id == $sppUp->tahun_id ? 'selected' : '' }}>{{ $tahun->tahun }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-md-12">
                                <label for="TextInput" class="form-label mt-2 mb-3 fw-bold">Program dan Kegiatan<sup
                                        class="text-danger">*</sup></label>
                                <br>
                                <small class="text-danger error-text programDanKegiatanHitung-error"
                                    id="programDanKegiatanHitung-error"></small>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Program</th>
                                                <th scope="col">Kegiatan</th>
                                                <th scope="col">Jumlah Anggaran</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="body-program">
                                            {!! $programDanKegiatan !!}
                                        </tbody>
                                        <tfoot>
                                            <tr id="jumlah">
                                                <td colspan="2" class="fw-bold text-center">Total</td>
                                                <td id="total-jumlah-anggaran">-</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="padding: 0px 10px !important">
                                                    <button class="btn btn-light fw-bold col-12" type="button"
                                                        id="btn-tambah-program-kegiatan"><i class="fas fa-plus-circle"></i>
                                                        Tambah Program &
                                                        Kegiatan</button>
                                                </td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="TextInput" class="form-label mt-2 mb-3 fw-bold">Dokumen Pendukung<sup
                                        class="text-danger">*</sup></label>
                                <small class="text-danger error-text dokumenFileHitung-error"
                                    id="dokumenFileHitung-error"></small>
                                <div id="list-upload" class="row">
                                    @if (!($sppUp->status_validasi_ppk == 0 && $sppUp->status_validasi_asn == 0))
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
                                    @endif
                                    @foreach ($sppUp->dokumenSppUp as $dokumen)
                                        @component('dashboard.components.dynamicForm.spp', [
                                            'labelNama' => $dokumen->nama_dokumen,
                                            'nameFileDokumen' => $dokumen->id,
                                            'class' => 'col-4',
                                            'classDokumen' => 'file_dokumen_update',
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
            $('#bulan').val('{{ $sppUp->bulan }}').trigger('change.select2');
            $('#spp-up').addClass('active');
            cekProgramKegiatan();
            hitungTotal();
        })

        function hapus(id) {
            $("#box-upload-" + id).remove();
        }

        $('#btn-tambah-program-kegiatan').click(function() {
            $.ajax({
                url: "{{ url('append/spp-up-tu') }}",
                type: "GET",
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $('#body-program').append(response.html);
                        $('.select2').select2({
                            placeholder: "- Pilih Salah Satu -",
                            theme: "bootstrap",
                        })
                        $('.uang').mask('000.000.000.000.000.000.000', {
                            reverse: true
                        });
                        cekProgramKegiatan();
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
        })

        $(document).on("change", ".program", function() {
            let value = $(this).val();
            let key = $(this).attr("data-key");
            var $kegiatan = $('#kegiatan-' + key);
            let $jumlahAnggaran = $('#jumlah-anggaran-' + key);
            $.ajax({
                url: "{{ url('list/kegiatan') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'program': value,
                },
                success: function(response) {
                    if (response.length > 0) {
                        $kegiatan.html('');
                        $kegiatan.append('<option value="">Pilih kegiatan</option>');
                        $.each(response, function(key, value) {
                            $kegiatan.append('<option value="' + value.id + '">' + value
                                .nama + " (" + value.no_rek + ")" + '</option>');
                        })
                    } else {
                        $kegiatan.html('');
                    }

                    $jumlahAnggaran.html(0);
                    hitungTotal();
                },
                error: function(response) {
                    swal("Gagal", "Terjadi Kesalahan", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                },
            })
        })

        $(document).on("change", ".kegiatan", function() {
            let value = $(this).val();

            $('.kegiatan').not(this).each(function() {
                if ($(this).val() === value) {
                    $(this).val('').trigger('change.select2');
                }
            });
        })

        $(document).on("keyup", ".jumlah-anggaran", function() {
            hitungTotal();
        })

        $(document).on("click", ".btn-delete-program-kegiatan", function() {
            let key = $(this).attr("data-key");
            let $targetElement = $('.program-kegiatan[data-key="' + key + '"]');

            $targetElement.remove();
            cekProgramKegiatan();
            hitungTotal();
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
            var formData = new FormData(this);

            $('.program').each(function() {
                var nama = $(this).attr('name');
                formData.append('program[]', nama);
            });

            $('.kegiatan').each(function() {
                var nama = $(this).attr('name');
                formData.append('kegiatan[]', nama);
            });

            $('.jumlah-anggaran').each(function() {
                var nama = $(this).attr('name');
                formData.append('jumlahAnggaran[]', nama);
            });

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
                        url: "{{ url('spp-up/' . $sppUp->id) }}",
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
                            swal("Terjadi Kesalahan", {
                                buttons: false,
                                timer: 1500,
                                icon: "warning",
                            });
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });

        })

        function resetProgramDanKegiatan() {
            $('#body-program').html('');
        }

        function calculateTotalAnggaran() {
            let totalJumlahAnggaran = 0;

            $('.jumlah-anggaran').each(function() {
                let value = $(this).val().replace(/\./g, '');
                totalJumlahAnggaran += parseInt(value, 10);
            });

            return {
                totalJumlahAnggaran,
            };
        }

        function updateTotalDisplays() {
            let {
                totalJumlahAnggaran
            } = calculateTotalAnggaran();

            function safeFormatRupiah(value) {
                try {
                    return formatRupiah(value);
                } catch (error) {
                    return '0';
                }
            }

            $('#total-jumlah-anggaran').html(safeFormatRupiah(totalJumlahAnggaran));
        }

        function hitungTotal() {
            updateTotalDisplays();
        }

        function cekProgramKegiatan() {
            let totalProgramKegiatan = $('.program-kegiatan').length;

            if (totalProgramKegiatan == 0) {
                return $('#jumlah').hide();
            }
            return $('#jumlah').show();
        }
    </script>
@endpush
