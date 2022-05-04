@extends('dashboard.layouts.main')

@section('title')
    Dashboard
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
            <a href="#">Pages</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Starter Page</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">User Statistics</div>
                        <div class="card-tools">
                            @component('dashboard.components.buttons.add', [
                                'id' => 'catatan-anc',
                                'class' => '',
                                'url' => '/anc/create',
                                ])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
            </div>
        </div>
    </div>



    @component('dashboard.components.buttons.submit', [
        'label' => 'Simpan',
        ])
    @endcomponent

    @component('dashboard.components.buttons.process', [
        'id' => 'proses-anc',
        'type' => 'submit',
        ])
    @endcomponent

    @component('dashboard.components.buttons.edit', [
        'id' => 'modal-btn-ubah',
        ])
    @endcomponent
@endsection

@push('script')
@endpush
