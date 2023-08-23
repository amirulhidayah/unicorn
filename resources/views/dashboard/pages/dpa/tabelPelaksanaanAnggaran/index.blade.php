@extends('dashboard.layouts.main')

@section('title')
    Tabel Pelaksanaan Anggaran
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
            <a href="#">Tabel Pelaksanaan Anggaran</a>
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
                        <div class="card-title">Tabel Pelaksanaan Anggaran</div>
                        <div class="card-tools">
                            <button class="btn btn-primary" id="btn-export"> <i class="fas fa-file-export"></i> Export
                                DPA</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger" role="alert">
                            Pastikan anda memilih seluruh filter
                        </div>
                    @endif
                    <form id="form-export" action="{{ url('/tabel-pelaksanaan-anggaran/export') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="row align-items-end mb-4">
                            @csrf
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
@endsection

@push('script')
    <script>
        var tipe = '';
        var role = "{{ Auth::user()->role }}";
        var roleAdmin = ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran'];
        var kegiatanEdit = null;
        var programEdit = null;

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
    </script>

    <script>
        $(document).ready(function() {
            $('#tabel-pelaksanaan-anggaran').addClass('active');
            getTable();
        })

        $('.filter').change(function() {
            getTable();
        })

        function getTable() {
            var tahun = $('#tahun_filter').val();
            var sekretariatDaerah = $('#sekretariat_daerah').val();
            var bulanDari = $('#bulan_dari_filter').val();
            var bulanSampai = $('#bulan_sampai_filter').val();
            var jenisSpp = $('#jenis_spp_filter').val();
            $.ajax({
                url: "{{ url('tabel-pelaksanaan-anggaran/tabel') }}",
                type: 'POST',
                data: {
                    'tahun': tahun,
                    'sekretariat_daerah': sekretariatDaerah,
                    'bulan_dari': bulanDari,
                    'bulan_sampai': bulanSampai,
                    'jenis_spp': jenisSpp
                },
                success: function(response) {
                    console.log(response);
                    $('#tabel-spd').html(response);
                },
                error: function(response) {
                    console.log(response);
                    $('#tabel-spd').html('');
                }
            })
        }
    </script>
@endpush
