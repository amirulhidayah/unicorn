@extends('dashboard.layouts.main')

@section('title')
    Riwayat SPP GU
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
                        <div class="card-title">Riwayat : {{ $sppGu->nama }}</div>
                        <div class="card-tools">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @component('dashboard.components.widgets.timeline',
                        [
                            'spp' => $sppGu,
                            'daftarRiwayat' => $sppGu->riwayatSppGu,
                            'tipeSuratPenolakan' => $tipeSuratPenolakan,
                            'tipeSuratPengembalian' => $tipeSuratPengembalian,
                        ])
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#spp-gu').addClass('active');
        })
    </script>
@endpush
