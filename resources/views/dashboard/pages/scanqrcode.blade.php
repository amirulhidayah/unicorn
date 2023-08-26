@extends('dashboard.layouts.main')

@section('title')
    Dashboard
@endsection

@push('style')
    <style>
        #qr-reader {
            /* height: 300px; */
            /* width: 300px; */
        }

        .media {
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.25) !important;
            border: 1px solid grey;
            border-radius: 10px;
            padding: 15px;
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
            <a href="{{ url('/dashboard') }}">Dashboard</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Scan Qr Code</div>
                        <div class="card-tools">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="cari-qrcode" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="input-group mb-4">
                                    <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                                    <input class="form-control" placeholder="Masukkan Kode" type="text" id="kode"
                                        name="kode">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-search"></i>
                                        Cari</button>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="row mb-5">
                    <div class="col-12 pe-auto" id="scan-kembali" style="cursor: pointer">
                        <div class="icon icon-shape icon-md bg-gradient-warning shadow text-center d-flex justify-content-center align-items-center"
                            style="height : 200px;">
                            <div>
                                <i class="fas fa-camera fa-2x" style="color: black;"></i>
                                <h5 class="mt-3">Scan Kembali</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center d-flex justify-content-center align-items-center">
                        <div id="qr-reader" class="mx-3"></div>
                    </div>
                </div>
                <div class="row" id="data">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        $(document).ready(function() {
            $('#scan-qrcode').addClass('active');
        });

        $(document).ready(function() {
            $('#scan-kembali').hide();
            $('#data').hide();
        })

        function onScanSuccess(decodedText, decodedResult) {
            $('#kode').val(decodedText);
            html5QrcodeScanner.clear(onScanSuccess);
            $('#qr-reader').hide();
            $('#scan-kembali').show();
            $('#cari-qrcode').submit();
        }

        $('#cari-qrcode').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ url('scan-qrcode') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        $('#data').html(response.html);
                        $('#data').show();
                        $("#data").get(0).scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        $('#data').hide();
                        $('#data').html('');
                        swal("Data tidak ditemukan", "Silahkan masukkan qr code yang benar", {
                            icon: "error",
                            buttons: false,
                            timer: 1000,
                        });
                    }
                    $('#qr-reader').hide();
                    $('#scan-kembali').show();
                },
                error: function(response) {
                    swal("Gagal", "Data Gagal Diproses", {
                        icon: "error",
                        buttons: false,
                        timer: 1000,
                    });
                },
            });
        })
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                // qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess);

        $('#scan-kembali').click(function() {
            $('#kode').val('');
            $('#qr-reader').show();
            $('#scan-kembali').hide();
            $('#data').html('');
            html5QrcodeScanner.render(onScanSuccess);
        })
    </script>
@endpush
