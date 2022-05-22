<div class="modal fade" id="modal-verifikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Verifikasi Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @csrf
            <div class="modal-body">
                @component('dashboard.components.formElements.select',
                    [
                        'label' => 'Verifikasi',
                        'id' => 'verifikasi',
                        'name' => 'verifikasi',
                        'class' => 'select2',
                        'wajib' => '<sup class="text-danger">*</sup>',
                    ])
                    @slot('options')
                        <option value="1">Terima</option>
                        <option value="2">Tolak</option>
                    @endslot
                @endcomponent
                <div id="div-alasan">
                    @component('dashboard.components.formElements.textArea',
                        [
                            'label' => 'Alasan',
                            'id' => 'alasan',
                            'name' => 'alasan',
                            'wajib' => '<sup class="text-danger">*</sup>',
                            'placeholder' => 'Masukkan Alasan',
                        ])
                    @endcomponent
                </div>

            </div>
            <div class="modal-footer">
                @component('dashboard.components.buttons.close')
                @endcomponent
                @component('dashboard.components.buttons.submit',
                    [
                        'label' => 'Simpan',
                    ])
                @endcomponent
            </div>
        </div>
    </div>
</div>
