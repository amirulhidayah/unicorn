<form id="{{ $form_id }}" action="#" method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-sm-12 col-lg-4">
            @component('dashboard.components.formElements.input', [
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
            @component('dashboard.components.formElements.input', [
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
            @component('dashboard.components.formElements.select', [
                'label' => 'Role',
                'id' => 'role',
                'name' => 'role',
                'class' => 'select2',
                'wajib' => '<sup class="text-danger">*</sup>',
                ])
                @slot('options')
                    <option value="Admin">Admin</option>
                    <option value="ASN Sub Bagian Keuangan">ASN Sub Bagian Keuangan</option>
                    <option value="Bendahara Pembantu">Bendahara Pembantu</option>
                    <option value="Bendahara Pengeluaran">Bendahara Pengeluaran</option>
                    <option value="PPK">PPK</option>
                @endslot
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-6">
            @component('dashboard.components.formElements.input', [
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
            @component('dashboard.components.formElements.select', [
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
            @component('dashboard.components.formElements.input', [
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
            @component('dashboard.components.formElements.select', [
                'label' => 'Biro Organisasi',
                'id' => 'biro_organisasi',
                'name' => 'biro_organisasi',
                'class' => 'select2',
                'wajib' => '<sup class="text-danger">*</sup>',
                ])
                @slot('options')
                    @foreach ($daftarBiroOrganisasi as $biroOrganisasi)
                        <option value="{{ $biroOrganisasi->id }}">{{ $biroOrganisasi->nama }}</option>
                    @endforeach
                @endslot
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-6">
            @component('dashboard.components.formElements.input', [
                'label' => 'Nomor Hp',
                'type' => 'text',
                'id' => 'nomor_hp',
                'name' => 'nomor_hp',
                'wajib' => '<sup class="text-danger">*</sup>',
                'placeholder' => 'Masukkan Nomor Hp',
                ])
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-6">
            @component('dashboard.components.formElements.input', [
                'label' => 'NIP',
                'type' => 'text',
                'id' => 'nip',
                'name' => 'nip',
                'wajib' => '',
                'placeholder' => 'Masukkan NIP',
                ])
            @endcomponent
        </div>
        <div class="col-sm-12 col-lg-12 mt-2">
            <label for="errorInput">Foto<sup class="text-danger">*</sup></label>
            <div id='img_contain'><img id="blah" align='middle'
                    src="{{ asset('assets/dashboard/img/profil-empty.png') }}" alt="" title='' /></div>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" id="inputGroupFile01" class="imgInp custom-file-input"
                        aria-describedby="inputGroupFileAddon01" name="foto">
                    <label class="custom-file-label" for="inputGroupFile01">Tambah Foto</label>
                </div>
            </div>
            <small class="form-text text-muted">Ukuran Foto Harus Dibawah 1 Mb</small>
            <span class="text-danger error-text foto-error"></span>
        </div>
        <div class="col-12 text-right">
            @component('dashboard.components.buttons.submit', [
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
                        printErrorMsg(response.error);
                    }
                },
                error: function(response) {
                    alert(response.responseJSON.message)
                },
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
        $("#inputGroupFile01").change(function(event) {
            RecurFadeIn();
            readURL(this);
        });
        $("#inputGroupFile01").on('click', function(event) {
            RecurFadeIn();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = $("#inputGroupFile01").val();
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                reader.onload = function(e) {
                    debugger;
                    $('#blah').attr('src', e.target.result);
                    $('#blah').hide();
                    $('#blah').fadeIn(500);
                    $('.custom-file-label').text(filename);
                }
                reader.readAsDataURL(input.files[0]);
            }
            $(".alert").removeClass("loading").hide();
        }

        function RecurFadeIn() {
            console.log('ran');
            FadeInAlert("Wait for it...");
        }

        function FadeInAlert(text) {
            $(".alert").show();
            $(".alert").text(text).addClass("loading");
        }
    </script>
@endpush
