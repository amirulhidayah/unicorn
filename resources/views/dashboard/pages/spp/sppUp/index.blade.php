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
                            @if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']))
                                @component('dashboard.components.buttons.add',
                                    [
                                        'id' => 'btn-tambah',
                                        'class' => '',
                                        'url' => url('spp-up/create'),
                                    ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card fieldset">
                                @component('dashboard.components.widgets.filter')
                                    @slot('daftarBiroOrganisasi', $daftarBiroOrganisasi)
                                    @slot('daftarTahun', $daftarTahun)
                                @endcomponent
                                @component('dashboard.components.dataTables.index',
                                    [
                                        'id' => 'table-data',
                                        'th' => ['No', 'Tanggal', 'Kegiatan', 'Program', 'Sekretariat Daerah', 'Periode', 'Jumlah Anggaran', 'Verifikasi ASN Sub Bagian Keuangan', 'Verifikasi PPK', 'Verifikasi Akhir', 'Aksi'],
                                    ])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
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
                url: "{{ url('spp-up') }}",
                data: function(d) {
                    d.biro_organisasi_id = $('#biro_organisasi').val();
                    d.tahun = $('#tahun').val();
                    d.status = $('#status').val();
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
                    data: 'tanggal_dibuat',
                    name: 'tanggal_dibuat',
                    className: 'text-center',
                },
                {
                    data: 'nama',
                    name: 'nama',
                    className: 'text-center',
                },
                {
                    data: 'program',
                    name: 'program',
                    className: 'text-center',
                },
                {
                    data: 'biro_organisasi',
                    name: 'biro_organisasi',
                    className: 'text-center',
                },
                {
                    data: 'periode',
                    name: 'periode',
                    className: 'text-center',
                },
                {
                    data: 'jumlah_anggaran',
                    name: 'jumlah_anggaran',
                    className: 'text-center',
                },
                {
                    data: 'verifikasi_asn',
                    name: 'verifikasi_asn',
                    className: 'text-center',
                },
                {
                    data: 'verifikasi_ppk',
                    name: 'verifikasi_ppk',
                    className: 'text-center',
                },
                {
                    data: 'status_verifikasi_akhir',
                    name: 'status_verifikasi_akhir',
                    className: 'text-center',
                    orderable: true,
                    searchable: true
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
                                    table.draw();
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
