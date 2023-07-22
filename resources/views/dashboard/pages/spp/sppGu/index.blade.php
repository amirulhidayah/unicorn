@extends('dashboard.layouts.main')

@section('title')
    SPP GU
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
            <a href="#">SPP GU</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Data SPP GU</div>
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
                                ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @livewire('dashboard.spp.spp-gu.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('dashboard.components.widgets.spmSp2d', [
        'spp' => 'spp-gu',
    ])
    @endcomponent
@endsection

@push('script')
    <script>
        $('#btn-tambah').click(function() {
            $.ajax({
                url: "{{ url('spp-gu/cek-sp2d') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = "{{ url('spp-gu/create') }}";
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
                        url: "{{ url('spp-gu') }}" + '/' + id,
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
                        url: "{{ url('spp-gu/verifikasi-akhir/') }}" + '/' + id,
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
    </script>

    <script>
        $(document).ready(function() {
            $('#spp-gu').addClass('active');
        })
    </script>
@endpush
