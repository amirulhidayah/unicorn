@extends('dashboard.layouts.main')

@section('title')
    Profil
@endsection

@push('style')
    <style>
        .preview-img {
            max-height: 256px;
            height: auto;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            padding: 5px;
        }

        #img_contain {
            border-radius: 5px;
            /*  border:1px solid grey;*/
            width: auto;
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
            <a href="#">Profil</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Profil</div>
                        <div class="card-tools">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-ubah" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-12">
                                <small class="form-text text-muted mb-2">Kosongkan password / foto / tanda tangan jika tidak
                                    ingin merubah
                                    datanya</small>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Email',
                                        'type' => 'text',
                                        'id' => 'email',
                                        'name' => 'email',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Alamat Email',
                                        'value' => Auth::user()->email,
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Password',
                                        'type' => 'text',
                                        'id' => 'password',
                                        'name' => 'password',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Password',
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Nama Lengkap',
                                        'type' => 'text',
                                        'id' => 'nama',
                                        'name' => 'nama',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Nama Lengkap',
                                        'value' => Auth::user()->profil->nama,
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.select',
                                    [
                                        'label' => 'Jenis Kelamin',
                                        'id' => 'jenis_kelamin',
                                        'name' => 'jenis_kelamin',
                                        'class' => 'select2',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                    ])
                                    @slot('options')
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-12">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Alamat',
                                        'type' => 'text',
                                        'id' => 'alamat',
                                        'name' => 'alamat',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Alamat',
                                        'value' => Auth::user()->profil->alamat,
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'Nomor Hp',
                                        'type' => 'text',
                                        'id' => 'nomor_hp',
                                        'name' => 'nomor_hp',
                                        'class' => 'numerik',
                                        'wajib' => '<sup class="text-danger">*</sup>',
                                        'placeholder' => 'Masukkan Nomor Hp',
                                        'value' => Auth::user()->profil->nomor_hp,
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                @component('dashboard.components.formElements.input',
                                    [
                                        'label' => 'NIP',
                                        'type' => 'text',
                                        'id' => 'nip',
                                        'name' => 'nip',
                                        'wajib' => '',
                                        'class' => 'numerik',
                                        'placeholder' => 'Masukkan NIP',
                                        'value' => Auth::user()->profil->nip,
                                    ])
                                @endcomponent
                            </div>
                            <div class="col-sm-12 col-lg-6 mt-3">
                                <label for="errorInput">Foto<sup class="text-danger">*</sup></label>
                                <div id='img_contain'><img id="preview-foto" align='middle'
                                        src="{{ Storage::url('profil/' . Auth::user()->profil->foto) }}" alt=""
                                        title='' class="preview-img" /></div>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="foto" class="imgInp custom-file-input"
                                            aria-describedby="inputGroupFileAddon01" name="foto">
                                        <label id="label-foto" class="custom-file-label" for="foto">Tambah Foto</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Ukuran Foto Harus Dibawah 1 Mb</small>
                                <span class="text-danger error-text foto-error"></span>
                            </div>
                            <div class="col-sm-12 col-lg-6 mt-3">
                                <label for="errorInput">Tanda Tangan<sup class="text-danger">*</sup></label>
                                <div id='img_contain'><img id="preview-tanda-tangan" align='middle'
                                        src="{{ Storage::url('tanda_tangan/' . Auth::user()->profil->tanda_tangan) }}"
                                        alt="" title='' class="preview-img" /></div>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" id="tanda-tangan" class="imgInp custom-file-input"
                                            aria-describedby="inputGroupFileAddon01" name="tanda_tangan">
                                        <label class="custom-file-label" id="label-tanda-tangan" for="tanda-tangan">Tambah
                                            Tanda
                                            Tangan</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Ukuran Tanda Tangan Harus Dibawah 1 Mb dan berformat
                                    PNG</small>
                                <span class="text-danger error-text tanda_tangan-error"></span>
                            </div>
                            <div class="col-12 text-right">
                                @component('dashboard.components.buttons.submit',
                                    [
                                        'label' => 'Simpan',
                                    ])
                                @endcomponent
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#jenis_kelamin').val("{{ Auth::user()->profil->jenis_kelamin }}").trigger('change');
        })

        $('#form-ubah').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

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
                        type: "POST",
                        url: "{{ url('/profil/' . Auth::user()->id) }}",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                swal("Berhasil", "Profil Berhasil Disimpan", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                }).then(function() {
                                    window.location.href =
                                        "{{ url('/logout') }}";
                                })
                            } else {
                                swal("Periksa Kembali Data Anda", {
                                    buttons: false,
                                    timer: 1500,
                                    icon: "warning",
                                });
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal("Gagal", "Terjadi Kesalahan", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                            alert(response.responseJSON.message)
                        },
                    });
                }
            });

        });

        $("#foto").change(function(event) {
            RecurFadeIn();
            readURLFoto(this);
        });
        $("#foto").on('click', function(event) {
            RecurFadeIn();
        });

        function readURLFoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#foto").val();
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                reader.onload = function(e) {
                    debugger;
                    $('#preview-foto').attr('src', e.target.result);
                    $('#preview-foto').hide();
                    $('#preview-foto').fadeIn(500);
                    $('#label-foto').text(filename);
                }
                reader.readAsDataURL(input.files[0]);
            }
            $(".alert").removeClass("loading").hide();
        }

        function RecurFadeIn() {
            FadeInAlert("Wait for it...");
        }

        function FadeInAlert(text) {
            $(".alert").show();
            $(".alert").text(text).addClass("loading");
        }

        $("#tanda-tangan").change(function(event) {
            RecurFadeIn();
            readURLTandaTangan(this);
        });
        $("#tanda-tangan").on('click', function(event) {
            RecurFadeIn();
        });

        function readURLTandaTangan(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#tanda-tangan").val();
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                reader.onload = function(e) {
                    debugger;
                    $('#preview-tanda-tangan').attr('src', e.target.result);
                    $('#preview-tanda-tangan').hide();
                    $('#preview-tanda-tangan').fadeIn(500);
                    $('#label-tanda-tangan').text(filename);
                }
                reader.readAsDataURL(input.files[0]);
            }
            $(".alert").removeClass("loading").hide();
        }

        function RecurFadeIn() {
            FadeInAlert("Wait for it...");
        }

        function FadeInAlert(text) {
            $(".alert").show();
            $(".alert").text(text).addClass("loading");
        }

        const printErrorMsg = (msg) => {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').text(value);
            });
        }
    </script>
@endpush
