@extends('dashboard.layouts.main')

@section('title')
    Daftar Kegiatan DPA
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
            <a href="#">Master Data</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Daftar Kegiatan DPA</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Data Daftar Kegiatan DPA</div>
                        <div class="card-tools">
                            @component('dashboard.components.buttons.add', [
                                'id' => 'btn-tambah',
                                'class' => '',
                                'url' => '#',
                            ])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @livewire('dashboard.master-data.kegiatan.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-tambah">
        <form method="POST" id="form-tambah">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-tambah-title">Tambah Daftar Kegiatan DPA</h5>
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
                                    'label' => 'Nama Daftar Kegiatan DPA',
                                    'placeholder' => 'Tambah Daftar Kegiatan DPA',
                                    'name' => 'nama',
                                    'required' => true,
                                ])
                                @endcomponent
                            </div>
                            <div class="col-lg-12">
                                @component('dashboard.components.formElements.input', [
                                    'id' => 'no_rek',
                                    'type' => 'text',
                                    'label' => 'No. Rekening Kegiatan SKPD',
                                    'placeholder' => 'Tambah Nomor Rekening Kegiatan SKPD',
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
        var idEdit = '';
        var idProgram = "{{ $idProgram }}";
        var aksiTambah = 'tambah';
        $('#btn-tambah').click(function() {
            resetError();
            aksiTambah = 'tambah';
            $('#nama').val('');
            $('#no_rek').val('');
            $('#modal-tambah').modal('show');
            $('#modal-tambah-title').html('Tambah Daftar Kegiatan DPA');
        })

        $(document).on('click', '#btn-edit', function() {
            resetError();
            let id = $(this).attr('data-value');
            idEdit = id;

            $.ajax({
                url: "{{ url('master-data/kegiatan/' . $idProgram . '/') }}" + '/' + id + '/edit',
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    aksiTambah = 'ubah';
                    $('#modal-tambah').modal('show');
                    $('#modal-tambah-title').html('Ubah Daftar Kegiatan DPA');
                    $('#nama').val(response.nama);
                    $('#no_rek').val(response.no_rek).change();
                },
            })
        })

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            resetError();
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
                            url: "{{ url('master-data/kegiatan/' . $idProgram . '/') }}",
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.status == 'success') {
                                    $('#modal-tambah').modal('hide');
                                    Livewire.emit('refreshTable');
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
                    } else {
                        $.ajax({
                            url: "{{ url('master-data/kegiatan/' . $idProgram . '/') }}" +
                                '/' + idEdit,
                            type: 'PUT',
                            data: $(this).serialize(),
                            success: function(response) {
                                if (response.status == 'success') {
                                    $('#modal-tambah').modal('hide');
                                    Livewire.emit('refreshTable');
                                    swal("Berhasil", "Data Berhasil Diubah", {
                                        icon: "success",
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

        $(document).on('click', '#btn-delete', function() {
            let id = $(this).attr('data-value');
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
                        url: "{{ url('master-data/kegiatan/' . $idProgram . '/') }}" + '/' +
                            id,
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
    </script>

    <script>
        $(document).ready(function() {
            $('#master-program').addClass('active');
        })
    </script>
@endpush
