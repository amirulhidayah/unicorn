@extends('dashboard.layouts.main')

@section('title')
    SPD
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
            <a href="#">SPD</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">SPD</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">SPD</div>
                        <div class="card-tools">
                            @if (Auth::user()->role == 'Admin')
                                @component('dashboard.components.buttons.import',
                                    [
                                        'id' => 'btn-import',
                                        'class' => '',
                                        'label' => 'Import SPD',
                                    ])
                                @endcomponent
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        @if (Auth::user()->role != 'Bendahara Pengeluaran')
                            <div class="col">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Biro Organisasi',
                                        'id' => 'biro_organisasi',
                                        'name' => 'biro_organisasi',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @slot('options')
                                        <option value="Semua">Semua</option>
                                        @foreach ($biroOrganisasi as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                        @endif

                        <div class="col">
                            @component('dashboard.components.formElements.select',
                                [
                                    'label' => 'Tahun',
                                    'id' => 'tahun_filter',
                                    'name' => 'tahun_filter',
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
                    </div>
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
                    <h5 class="modal-title">Import SPD</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <ol class="pl-3">
                            <li>Download Format Excel Berikut dan Sesuaikan Data SPD Sesuai Format Excel yang
                                Didownload
                                <br>
                                <a href="{{ url('spd/format-import') }}" class="btn btn-sm btn-primary mt-2"><i
                                        class="fas fa-file-excel"></i> Format Import
                                    Excel</a>
                            </li>
                            <li class="mt-2">Pilih Tahun SPD</li>
                            @component('dashboard.components.formElements.select',
                                [
                                    'id' => 'tahun',
                                    'name' => 'tahun',
                                    'label' => 'Tahun SPD',
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
                            <li class="mt-2">Pilih File Excel yang Didalamnya sudah terdapat file SPD yang sudah
                                disesuaikan dengan format
                                yang diberikan</li>
                            @component('dashboard.components.formElements.input',
                                [
                                    'id' => 'file_spd',
                                    'name' => 'file_spd',
                                    'type' => 'file',
                                    'label' => 'File SPD',
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
                        @component('dashboard.components.buttons.submit',
                            [
                                'id' => 'btn-submit',
                                'label' => 'Import SPD',
                            ])
                        @endcomponent
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#btn-import').click(function() {
            $('#modal-import').modal('show');
        });

        $('#form-import').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ url('spd/import') }}",
                type: 'POST',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modal-import').modal('hide');
                        swal("Berhasil", "Data Berhasil Diimport", {
                            icon: "success",
                            buttons: false,
                            timer: 1000,
                        });
                        window.location.replace("{{ url('/spd') }}");
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
        })
    </script>

    <script>
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').removeClass('d-none');
                $('.' + key + '-error').text(value);
            });
        }

        $(document).ready(function() {
            $('#spd').addClass('active');
        })

        $('#biro_organisasi').on('change', function() {
            tabelSpd();
        })

        $('#tahun_filter').on('change', function() {
            tabelSpd();
        })

        function tabelSpd() {
            var tahun = $('#tahun_filter').val();
            var biroOrganisasi = $('#biro_organisasi').val();
            $.ajax({
                url: "{{ url('spd/tabel-spd') }}",
                type: 'POST',
                data: {
                    'tahun': tahun,
                    'biro_organisasi': biroOrganisasi,
                },
                success: function(response) {
                    $('#tabel-spd').html(response);
                }
            })
        }
    </script>
@endpush
