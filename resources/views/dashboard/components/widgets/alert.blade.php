{{-- <style>
    #custom-alert .card-header {
        border: 1px solid grey;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
    }

    #custom-alert-judul {
        font-size: 18px;
    }
</style> --}}

{{-- <div class="card {{ $classBg }}" id="custom-alert">
    <div class="card-header bg-light text-dark">
        <span class="fw-bold" id="custom-alert-judul">{{ $judul ?? '' }}</span>
    </div>
    <div class="card-body">
        {{ $isi }}
    </div>
</div> --}}

@push('style')
    <style>
        .card-alert {
            border-radius: 10px;
            border: 1px solid rgb(24, 23, 23, 0.15);
            padding: 15px;
        }

        .c-details span {
            font-weight: 300;
            font-size: 13px
        }

        .icon {
            width: 50px;
            height: 50px;
            background-color: #eee;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 39px
        }

        .progress {
            height: 10px;
            border-radius: 10px
        }

        .progress div {
            background-color: red
        }

        .text1 {
            font-size: 14px;
            font-weight: 600
        }

        .text2 {
            color: #a5aec0
        }
    </style>
@endpush

<div class="card-alert p-3 mb-5">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row align-items-center">
            <div class="icon bg-danger text-light mr-2"> <i class="fas fa-times"></i> </div>
            <div class="ms-2 c-details">
                <h6 class="mb-0">Berkas Ditolak
                </h6> <span>{{ $oleh == 'ppk' ? 'PPK' : 'ASN Sub Bagian Keuangan' }},
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <h3 class="heading">{{ $isi }}</h3>
    </div>
</div>
