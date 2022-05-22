@extends('dashboard.layouts.main')

@section('title')
    Daftar Dokumen SPP TU
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
            <a href="#">Daftar Dokumen SPP TU</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Data Daftar Dokumen SPP TU</div>
                        <div class="card-tools">
                            @component('dashboard.components.buttons.add',
                                [
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
                            <div class="card fieldset">
                                @component('dashboard.components.dataTables.index',
                                    [
                                        'id' => 'table-data',
                                        'th' => ['No', 'Nama', 'Aksi'],
                                    ])
                                @endcomponent
                            </div>
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
                        <h5 class="modal-title" id="modal-tambah-title">Tambah Daftar Dokumen SPP TU</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @component('dashboard.components.formElements.input',
                            [
                                'id' => 'nama',
                                'type' => 'text',
                                'label' => 'Nama Daftar Dokumen SPP TU',
                                'placeholder' => 'Tambah Daftar Dokumen SPP TU',
                                'name' => 'nama',
                                'required' => true,
                            ])
                        @endcomponent
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
        var idEdit = '';
        var aksiTambah = 'tambah';
        $('#btn-tambah').click(function() {
            aksiTambah = 'tambah';
            $('#modal-tambah').modal('show');
            $('#modal-tambah-title').html('Tambah Daftar Dokumen SPP TU');
        })

        $(document).on('click', '#btn-edit', function() {
            let id = $(this).val();
            idEdit = id;

            $.ajax({
                url: "{{ url('master-data/daftar-dokumen-spp-tu') }}" + '/' + id + '/edit',
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    aksiTambah = 'ubah';
                    $('#modal-tambah').modal('show');
                    $('#modal-tambah-title').html('Ubah Daftar Dokumen SPP TU');
                    $('#nama').val(response.nama);
                },
            })
        })

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            if (aksiTambah == 'tambah') {
                $.ajax({
                    url: "{{ url('master-data/daftar-dokumen-spp-tu') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#modal-tambah').modal('hide');
                            table.draw();
                            swal("Berhasil", "Data Berhasil Tersimpan", {
                                icon: "success",
                                buttons: false,
                                timer: 1000,
                            });
                            resetModal();
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
                    url: "{{ url('master-data/daftar-dokumen-spp-tu') }}" + '/' + idEdit,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#modal-tambah').modal('hide');
                            table.draw();
                            swal("Berhasil", "Data Berhasil Diubah", {
                                icon: "success",
                                buttons: false,
                                timer: 1000,
                            });
                            resetModal();
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
        })

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
                        url: "{{ url('master-data/daftar-dokumen-spp-tu') }}" + '/' + id,
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
                                    table.draw();
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

        var table = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    className: 'btn btn-sm btn-light-success px-2 btn-export-table d-inline ml-3 font-weight',
                    text: '<i class="bi bi-file-earmark-arrow-down"></i> Ekspor Data',
                    exportOptions: {
                        modifier: {
                            order: 'index', // 'current', 'applied', 'index',  'original'
                            page: 'all', // 'all',     'current'
                            search: 'applied' // 'none',    'applied', 'removed'
                        },
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'btn btn-sm btn-light-success px-2 btn-export-table d-inline ml-3 font-weight',
                    text: '<i class="bi bi-eye-fill"></i> Tampil/Sembunyi Kolom',
                }
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            ajax: {
                url: "{{ url('master-data/daftar-dokumen-spp-tu') }}",
                data: function(d) {
                    d.statusValidasi = $('#status-validasi').val();
                    d.kategori = $('#kategori').val();
                    d.search = $('input[type="search"]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama',
                    name: 'nama',
                    className: 'text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: true,
                    searchable: true
                },

            ],
            columnDefs: [
                // {
                //     targets: 4,
                //     visible: false,
                // },

            ],
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#master-daftar-dokumen-spp-tu').addClass('active');
        })

        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').removeClass('d-none');
                $('.' + key + '-error').text(value);
            });
        }

        function resetError() {
            resetErrorElement('nama');
        }

        function resetModal() {
            resetError();
            $('#form-tambah')[0].reset();
        }

        function resetErrorElement(key) {
            $('.' + key + '-error').addClass('d-none');
        }
    </script>
@endpush
