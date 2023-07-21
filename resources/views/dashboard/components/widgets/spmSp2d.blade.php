<div class="modal fade" id="modal-spm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" id="form-spm" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload SPM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @component('dashboard.components.formElements.input', [
                        'label' => 'Dokumen SPM',
                        'type' => 'file',
                        'id' => 'dokumen_spm',
                        'name' => 'dokumen_spm',
                        'accept' => 'application/pdf',
                    ])
                    @endcomponent
                </div>
                <div class="modal-footer">
                    @component('dashboard.components.buttons.close')
                    @endcomponent
                    @component('dashboard.components.buttons.submit', [
                        'label' => 'Upload',
                    ])
                    @endcomponent
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-sp2d" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form method="POST" id="form-sp2d" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Arsip SP2D</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @component('dashboard.components.formElements.input', [
                        'label' => 'Dokumen Arsip SP2D',
                        'type' => 'file',
                        'id' => 'dokumen_arsip_sp2d',
                        'name' => 'dokumen_arsip_sp2d',
                        'accept' => 'application/pdf',
                    ])
                    @endcomponent
                </div>
                <div class="modal-footer">
                    @component('dashboard.components.buttons.close')
                    @endcomponent
                    @component('dashboard.components.buttons.submit', [
                        'label' => 'Upload',
                    ])
                    @endcomponent
                </div>
            </div>
        </div>
    </form>
</div>

@push('script')
    <script>
        let idSpm = null;
        let idSp2d = null;

        $(document).on('click', '#btn-upload-spm', function() {
            let id = $(this).val();
            idSpm = id;
            $('#dokumen_spm').val('');
            $('#modal-spm').modal('show');
        })

        $(document).on('click', '#btn-upload-sp2d', function() {
            let id = $(this).val();
            idSp2d = id;
            $('#dokumen_arsip_sp2d').val('');
            $('#modal-sp2d').modal('show');
        })

        $('#form-spm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'info',
                text: "Pastikan data yang anda masukkan sudah benar !",
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
            }).then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "{{ url($spp . '/spm') }}" + '/' + idSpm,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-spm').modal('hide');
                                Livewire.emit('refreshTable');
                                swal("Berhasil", "Data Berhasil Diupload", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            swal("Gagal", "Data Gagal Diproses", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        }
                    })
                }
            });
        })

        $('#form-sp2d').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            swal({
                title: 'Apakah Anda Yakin ?',
                icon: 'info',
                text: "Pastikan data yang anda masukkan sudah benar !",
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
            }).then((confirm) => {
                if (confirm) {
                    $.ajax({
                        url: "{{ url($spp . '/sp2d') }}" + '/' + idSp2d,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'success') {
                                $('#modal-sp2d').modal('hide');
                                Livewire.emit('refreshTable');
                                swal("Berhasil", "Data Berhasil Diupload", {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });

                            } else {
                                printErrorMsg(response.error);
                            }
                        },
                        error: function(response) {
                            console.log(response.responseText);
                            swal("Gagal", "Data Gagal Diproses", {
                                icon: "error",
                                buttons: false,
                                timer: 1000,
                            });
                        }
                    })
                }
            });
        })
    </script>
@endpush
