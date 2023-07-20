@extends('dashboard.layouts.main')

@section('title')
    SPP UP
@endsection

@push('style')
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Data SPP UP</div>
                        <div class="card-tools">
                            @if (in_array(Auth::user()->role, [
                                    'Admin',
                                    'Bendahara Pengeluaran',
                                    'Bendahara Pengeluaran Pembantu',
                                    'Bendahara Pengeluaran Pembantu Belanja Hibah',
                                ]))
                                @component('dashboard.components.buttons.add', [
                                    'id' => 'btn-tambah',
                                    'class' => '',
                                    // 'url' => url('spp-up/create'),
                                ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @livewire('dashboard.spp.spp-up.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-spm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form method="POST" id="form-spm" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Upload SPM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @component('dashboard.components.formElements.input', [
                            'label' => 'Dokumen SPM',
                            'type' => 'file',
                            'id' => 'dokumen_spm',
                            'name' => 'dokumen_spm',
                            'accept' => 'application/pdf',
                        ])
                        @endcomponent
                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit', [
                            'label' => 'Upload',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modal-sp2d" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form method="POST" id="form-sp2d" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Upload Arsip SP2D</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @component('dashboard.components.formElements.input', [
                            'label' => 'Dokumen Arsip SP2D',
                            'type' => 'file',
                            'id' => 'dokumen_arsip_sp2d',
                            'name' => 'dokumen_arsip_sp2d',
                            'accept' => 'application/pdf',
                        ])
                        @endcomponent
                    </div>
                    <div class="modal-footer">
                        @component('dashboard.components.buttons.close')
                        @endcomponent
                        @component('dashboard.components.buttons.submit', [
                            'label' => 'Upload',
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    @if (Session::has('error'))
        <script>
            swal("Selesaikan Terlebih Dahulu Arsip SP2D", 'Terdapat arsip SP2D yang belum diupload', {
                icon: "error",
                buttons: false,
                timer: 5000,
            });
        </script>
    @endif

    <script>
        let idSpm = null;
        let idSp2d = null;

        $('#btn-tambah').click(function() {
            $.ajax({
                url: "{{ url('spp-up/cek-sp2d') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = "{{ url('spp-up/create') }}";
                    } else {
                        swal("Selesaikan Terlebih Dahulu Arsip SP2D", response.message, {
                            icon: "error",
                            buttons: false,
                            timer: 5000,
                        });
                    }
                },
                error: function(response) {
                    swal("Gagal", "Gagal Memproses", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                }
            })
        });

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
                        url: "{{ url('spp-up') }}" + '/' + id,
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
                                    Livewire.emit('refreshTable');
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

        $(document).on('click', '#btn-upload-spm', function() {
            let id = $(this).val();
            idSpm = id;
            $('#dokumen_spm').val('');
            $('#modal-spm').modal('show');
        })

        $(document).on('click', '#btn-upload-sp2d', function() {
            let id = $(this).val();
            idSp2d = id;
            $('#dokumen_arsip_sp2d').val('');
            $('#modal-sp2d').modal('show');
        })

        $('#form-spm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'info',
                text: "Pastikan data yang anda masukkan sudah benar !",
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
            }).then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "{{ url('spp-up/spm') }}" + '/' + idSpm,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-spm').modal('hide');
                                Livewire.emit('refreshTable');
                                swal("Berhasil", "Data Berhasil Diupload", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal("Gagal", "Data Gagal Diproses", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        }
                    })
                }
            });
        })

        $('#form-sp2d').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'info',
                text: "Pastikan data yang anda masukkan sudah benar !",
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
            }).then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "{{ url('spp-up/sp2d') }}" + '/' + idSp2d,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-sp2d').modal('hide');
                                Livewire.emit('refreshTable');
                                swal("Berhasil", "Data Berhasil Diupload", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });

                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal("Gagal", "Data Gagal Diproses", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        }
                    })
                }
            });
        })

        $(document).on('click', '#btn-verifikasi', function() {
            let id = $(this).val();
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'info',
                text: "Status Data Akan Dinyatakan Selesai Jika Anda Menekan Tombol Ya",
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
            }).then((Delete) => {
                if (Delete) {
                    $.ajax({
                        url: "{{ url('spp-up/verifikasi-akhir/') }}" + '/' + id,
                        type: 'PUT',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                swal("Berhasil", "Data Berhasil Diselesaikan", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                }).then(function() {
                                    Livewire.emit('refreshTable');
                                })
                            } else {
                                swal("Gagal", "Data Gagal Diselesaikan", {
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

        $(document).ready(function() {
            $('#spp-up').addClass('active');
        })
    </script>
@endpush
