@php
    $randomCard = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
    $randomNamaFile = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 10);
@endphp

<div class="card box-upload" id="box-upload-{{ $randomCard }}" class="box-upload">
    <div class="card-body">
        <div class="row">
            <!-- <div class="d-flex border rounded shadow shadow-lg p-2 "> -->
            <div class="col-3 d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="70px">
            </div>
            <div class="col-9">
                <div class="mb-3 mt-2">
                    <input type="text" class="form-control {{ $classNama ?? '' }}" id="nama_file"
                        name="{{ $randomNamaFile }}" placeholder="Masukkan Nama File" value="{{ $labelNama ?? '' }}">
                </div>
                <div class="mb-3">
                    <input name="{{ $nameFileDokumen }}" class="form-control {{ $classDokumen ?? '' }}" type="file"
                        accept="application/pdf">
                    <p class="text-danger error-text {{ $randomNamaFile }}-error my-0"></p>
                    <p class="text-danger error-text {{ $nameFileDokumen }}-error my-0"></p>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-danger fw-bold card-footer bg-danger text-center p-0"
        onclick="hapus('{{ $randomCard }}')"><i class="fas fa-trash-alt"></i>
        Hapus</button>
</div>
