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
                            @component('dashboard.components.buttons.import',
                                [
                                    'id' => 'btn-import',
                                    'class' => '',
                                    'label' => 'Import SPD',
                                ])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (count($daftarBiroOrganisasi) > 0)
                        <table class="table table-bordered table-responsive">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                                    <th scope="col">NO. REK. KEG. SKPD</th>
                                    <th scope="col">JUMLAH ANGGARAN TAHUN INI</th>
                                    <th scope="col">TW 1</th>
                                    <th scope="col">TW 2</th>
                                    <th scope="col">TW 3</th>
                                    <th scope="col">TW 4</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarBiroOrganisasi as $biroOrganisasi)
                                    <tr>
                                        <td colspan="8" class="fw-bold">{{ $biroOrganisasi->nama }}</td>
                                    </tr>
                                    @php
                                        $daftarProgram = \App\Models\Program::with('kegiatan')
                                            ->whereHas('kegiatan', function ($query) use ($biroOrganisasi) {
                                                $query->whereHas('spd', function ($query) use ($biroOrganisasi) {
                                                    $query->where('biro_organisasi_id', $biroOrganisasi->id);
                                                });
                                            })
                                            ->get();
                                    @endphp
                                    @foreach ($daftarProgram as $program)
                                        <tr>
                                            <td colspan="2">{{ $program->nama }}</td>
                                            <td colspan="6">{{ $program->no_rek }}</td>
                                        </tr>
                                        @php
                                            $daftarSpd = \App\Models\Spd::with(['biroOrganisasi', 'kegiatan', 'tahun'])
                                                ->whereHas('kegiatan', function ($query) use ($program) {
                                                    $query->where('program_id', $program->id);
                                                })
                                                ->where('biro_organisasi_id', $biroOrganisasi->id)
                                                ->where('tahun_id', $tahunId)
                                                ->get();

                                            $totalJumlahAnggaran = 0;
                                            $totalTw1 = 0;
                                            $totalTw2 = 0;
                                            $totalTw3 = 0;
                                            $totalTw4 = 0;
                                            $totalTw5 = 0;
                                        @endphp
                                        @foreach ($daftarSpd as $spd)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $spd->kegiatan->nama }}</td>
                                                <td>{{ $spd->kegiatan->no_rek }}</td>
                                                <td>Rp.
                                                    {{ number_format($spd->tw1 + $spd->tw2 + $spd->tw3 + $spd->tw4, 0, ',', '.') }}
                                                </td>
                                                <td>Rp. {{ number_format($spd->tw1, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($spd->tw2, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($spd->tw3, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($spd->tw4, 0, ',', '.') }}</td>
                                            </tr>
                                            @php
                                                $totalJumlahAnggaran = $totalJumlahAnggaran + $spd->tw1 + $spd->tw2 + $spd->tw3 + $spd->tw4;
                                                $totalTw1 += $spd->tw1;
                                                $totalTw2 += $spd->tw2;
                                                $totalTw3 += $spd->tw3;
                                                $totalTw4 += $spd->tw4;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>Jumlah</td>
                                            <td></td>
                                            <td>Rp.
                                                {{ number_format($totalJumlahAnggaran, 0, ',', '.') }}
                                            </td>
                                            <td>Rp.
                                                {{ number_format($totalTw1, 0, ',', '.') }}
                                            </td>
                                            <td>Rp.
                                                {{ number_format($totalTw2, 0, ',', '.') }}
                                            </td>
                                            <td>Rp.
                                                {{ number_format($totalTw3, 0, ',', '.') }}
                                            </td>
                                            <td>Rp.
                                                {{ number_format($totalTw4, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
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
    </script>
@endpush
