@extends('dashboard.layouts.main')

@section('title')
    Tentang
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
            <a href="#">Master Data</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tentang</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Tentang</div>
                        <div class="card-tools">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-tambah" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <textarea id="summernote" name="isi">{!! $tentang->isi !!}</textarea>
                        <span class="text-danger error-text isi-error"></span>
                        <button type="submit" class="btn btn-success col-12"><i class="fas fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#summernote').summernote({
            placeholder: 'Masukkan Tentang',
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
            tabsize: 2,
            height: 400,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['media', ['picture', 'link']]
            ]
        });

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'warning',
                text: "Apakah Anda Yakin ?",
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Update) => {
                if (Update) {
                    $.ajax({
                        url: "{{ url('master-data/tentang') }}" + '/' + "{{ $tentang->id }}",
                        type: 'PUT',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status == 'success') {
                                swal("Berhasil", "Data Berhasil Diubah", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal("Gagal", "Data Gagal Diubah", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        }
                    })
                }
            });

        })

        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').removeClass('d-none');
                $('.' + key + '-error').text(value);
            });
        }

        $(document).ready(function() {
            $('#master-tentang').addClass('active');
        })
    </script>
@endpush
