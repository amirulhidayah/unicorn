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
            <a href="{{ url('/dashboard') }}">Dashboard</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5><b>Dokumen SPP UP</b></h5>
                                </div>
                                <h3 class="text-success fw-bold">{{ $sppUp['totalDokumen'] }}</h3>
                            </div>
                            <hr class="mt-0 mb-2">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-primary">Belum Diproses</span></p>
                                <p class="text-muted mb-0">{{ $sppUp['belumProses'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-danger">Ditolak</span></p>
                                <p class="text-muted mb-0">{{ $sppUp['ditolak'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-success">Selesai</span></p>
                                <p class="text-muted mb-0">{{ $sppUp['selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5><b>Dokumen SPP TU</b></h5>
                                </div>
                                <h3 class="text-success fw-bold">{{ $sppTu['totalDokumen'] }}</h3>
                            </div>
                            <hr class="mt-0 mb-2">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-primary">Belum Diproses</span></p>
                                <p class="text-muted mb-0">{{ $sppTu['belumProses'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-danger">Ditolak</span></p>
                                <p class="text-muted mb-0">{{ $sppTu['ditolak'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-success">Selesai</span></p>
                                <p class="text-muted mb-0">{{ $sppTu['selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5><b>Dokumen SPP LS</b></h5>
                                </div>
                                <h3 class="text-success fw-bold">{{ $sppLs['totalDokumen'] }}</h3>
                            </div>
                            <hr class="mt-0 mb-2">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-primary">Belum Diproses</span></p>
                                <p class="text-muted mb-0">{{ $sppLs['belumProses'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-danger">Ditolak</span></p>
                                <p class="text-muted mb-0">{{ $sppLs['ditolak'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-success">Selesai</span></p>
                                <p class="text-muted mb-0">{{ $sppLs['selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5><b>Dokumen SPP GU</b></h5>
                                </div>
                                <h3 class="text-success fw-bold">{{ $sppGu['totalDokumen'] }}</h3>
                            </div>
                            <hr class="mt-0 mb-2">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-primary">Belum Diproses</span></p>
                                <p class="text-muted mb-0">{{ $sppGu['belumProses'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-danger">Ditolak</span></p>
                                <p class="text-muted mb-0">{{ $sppGu['ditolak'] }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-1"><span class="badge badge-success">Selesai</span></p>
                                <p class="text-muted mb-0">{{ $sppGu['selesai'] }}</p>
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
        $(document).ready(function() {
            $('#dashboard').addClass('active');
        });
    </script>
@endpush
