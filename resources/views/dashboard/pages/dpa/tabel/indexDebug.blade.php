@extends('dashboard.layouts.main')

@section('title')
    Dokumen Pelaksana Anggaran
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
            <a href="#">Dokumen Pelaksana Anggaran</a>
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
                        <div class="card-title">Dokumen Pelaksana Anggaran</div>
                        <div class="card-tools">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="tabel-spd">
                        <table class="table table-bordered table-responsive table-hover text-nowrap">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col" rowspan="2">No.</th>
                                    <th scope="col" rowspan="2">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                                    <th scope="col" rowspan="2">NO. REK. KEG. SKPD</th>
                                    <th scope="col" rowspan="2">Jumlah Anggaran</th>
                                    @foreach ($array['bulan'] as $bulan)
                                        <th scope="col" colspan="2">{{ $bulan }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($array['bulan'] as $bulan)
                                        <td>
                                            Anggaran Digunakan
                                        </td>
                                        <td>
                                            Sisa Anggaran
                                        </td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($array['data'] as $data)
                                    <tr>
                                        <td colspan="{{ 6 + 2 * count($array['bulan']) }}" class="fw-bold">
                                            {{ $data['sekretariat_daerah'] }}</td>
                                    </tr>
                                    @foreach ($data['program'] as $program)
                                        <tr>
                                            <td colspan="2">{{ $program['nama'] }}</td>
                                            <td colspan="{{ 5 + 2 * count($array['bulan']) }}">
                                                {{ $program['no_rek'] }}</td>
                                        </tr>
                                        @foreach ($program['kegiatan'] as $kegiatan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $kegiatan['nama'] }}</td>
                                                <td>{{ $kegiatan['no_rek'] }}</td>
                                                <td>
                                                    {{ 'Rp. ' . number_format($kegiatan['jumlah_anggaran'], 0, ',', '.') }}
                                                </td>
                                                @foreach ($kegiatan['bulan'] as $bulan)
                                                    <td>
                                                        {{ 'Rp. ' . number_format($bulan['anggaran_digunakan'], 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        {{ 'Rp. ' . number_format($bulan['sisa_anggaran'], 0, ',', '.') }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                Jumlah
                                            </td>
                                            <td> {{ 'Rp. ' . number_format($program['jumlah_anggaran'], 0, ',', '.') }}
                                            </td>
                                            @foreach ($program['total_bulan']['bulan'] as $total)
                                                {{-- @php
                                                    dd($total);
                                                @endphp --}}
                                                <td> {{ 'Rp. ' . number_format($total['anggaran_digunakan'], 0, ',', '.') }}
                                                </td>
                                                <td> {{ 'Rp. ' . number_format($total['sisa_anggaran'], 0, ',', '.') }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>

                            <tbody>
                                <tr>
                                    <td colspan="{{ Auth::user()->role == 'Admin' ? 29 : 28 }}"
                                        class="fw-bold text-center">
                                        Data
                                        Tidak Ada</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script></script>

    <script>
        $(document).ready(function() {
            $('#tabel-dpa').addClass('active');
        })
    </script>

    <script></script>
@endpush