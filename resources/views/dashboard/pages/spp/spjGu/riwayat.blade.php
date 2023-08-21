@extends('dashboard.layouts.main')

@section('title')
    Riwayat SPJ GU
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
            <a href="#">SPP GU</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">SPJ GU</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Riwayat : {{ $spjGu->nama }}</div>
                        <div class="card-tools">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @component('dashboard.components.widgets.timeline', [
                        'spp' => $spjGu,
                        'daftarRiwayat' => $spjGu->riwayatSpjGu,
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
            $('#menu-spp-gu').collapse('show');
            $('#spp-gu').addClass('active');
            $('#spp-gu-spj').addClass('active');
        })
    </script>
@endpush
