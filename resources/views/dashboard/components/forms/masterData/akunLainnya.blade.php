<form id="{{ $form_id }}" action="#" method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-12">
            <small class="form-text text-muted mb-2">Kosongkan password / foto / tanda tangan jika tidak ingin merubah
                datanya</small>
        </div>
        <div class="col-sm-12 col-lg-4">
            @component('dashboard.components.formElements.input',
                [
                    'label' => 'Email',
                    'type' => 'text',
                    'id' => 'email',
                    'name' => 'email',
                    'wajib' => '<sup class="text-danger">*</sup>',
                    'placeholder' => 'Masukkan Alamat Email',
                ])
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-4">
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
        <div class="col-sm-12 col-lg-4">
            @component('dashboard.components.formElements.select',
                [
                    'label' => 'Role',
                    'id' => 'role',
                    'name' => 'role',
                    'class' => 'select2',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                @slot('options')
                    <option value="Admin">Admin</option>
                    <option value="ASN Sub Bagian Keuangan">ASN Sub Bagian Keuangan</option>
                    <option value="Kuasa Pengguna Anggaran">Kuasa Pengguna Anggaran</option>
                    <option value="PPK">PPK</option>
                @endslot
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
        <div class="col-sm-12 col-lg-6">
            @component('dashboard.components.formElements.input',
                [
                    'label' => 'Alamat',
                    'type' => 'text',
                    'id' => 'alamat',
                    'name' => 'alamat',
                    'wajib' => '<sup class="text-danger">*</sup>',
                    'placeholder' => 'Masukkan Alamat',
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
                    'class' => 'numerik',
                    'wajib' => '',
                    'placeholder' => 'Masukkan NIP',
                ])
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-6">
            @component('dashboard.components.formElements.select',
                [
                    'label' => 'Aktif',
                    'id' => 'aktif',
                    'name' => 'aktif',
                    'class' => 'select2',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                @slot('options')
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                @endslot
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-6 mt-3">
            <label for="errorInput">Foto<sup class="text-danger">*</sup></label>
            <div id='img_contain'><img id="preview-foto" align='middle'
                    src="{{ asset('assets/dashboard/img/profil-empty.png') }}" alt="" title=''
                    class="preview-img" /></div>
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
                    src="{{ asset('assets/dashboard/img/tanda-tangan-empty.png') }}" alt="" title=''
                    class="preview-img" /></div>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="tanda-tangan" class="imgInp custom-file-input"
                        aria-describedby="inputGroupFileAddon01" name="tanda_tangan">
                    <label class="custom-file-label" id="label-tanda-tangan" for="tanda-tangan">Tambah Tanda
                        Tangan</label>
                </div>
            </div>
            <small class="form-text text-muted">Ukuran Tanda Tangan Harus Dibawah 1 Mb dan berformat PNG</small>
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


@push('script')
    @if (isset($method) && $method == 'PUT')
        <script>
            $(document).ready(function() {});
        </script>
    @endif
    <script>
        $('#{{ $form_id }}').submit(function(e) {
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
                        url: "{{ $action }}",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                swal("Berhasil", "Data Berhasil Disimpan", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                }).then(function() {
                                    window.location.href =
                                        "{{ $back_url }}";
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

        $(function() {
            $('.modal').modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        const printErrorMsg = (msg) => {
            $.each(msg, function(key, value) {
                $('.' + key + '-error').text(value);
            });
        }
    </script>

    <script>
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
    </script>
@endpush
