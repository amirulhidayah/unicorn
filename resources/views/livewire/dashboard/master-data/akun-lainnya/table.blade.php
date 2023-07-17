<div>
    <div>
        <div class="row mb-3" wire:ignore>
            <div class="col-sm-12 col-lg-6">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Aktif',
                    'id' => 'aktif',
                    'name' => 'aktif',
                    'class' => 'select2',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">Semua</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    @endslot
                @endcomponent
            </div>
            <div class="col-sm-12 col-lg-6">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Role',
                    'id' => 'role',
                    'name' => 'role',
                    'class' => 'select2',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">Semua</option>
                        <option value="Admin">Admin</option>
                        <option value="ASN Sub Bagian Keuangan">ASN Sub Bagian Keuangan</option>
                        <option value="Kuasa Pengguna Anggaran">Kuasa Pengguna Anggaran</option>
                        <option value="PPK">PPK</option>
                    @endslot
                @endcomponent
            </div>
        </div>
    </div>
    @component('dashboard.components.livewire.table', [
        'th' => [
            [
                'title' => 'No',
                'class' => 'text-center',
            ],
            [
                'title' => 'Nama',
            ],
            [
                'title' => 'Status',
            ],
            [
                'title' => 'Role',
            ],
            [
                'title' => 'Foto',
            ],
            [
                'title' => 'Aksi',
            ],
        ],
        'datas' => $datas,
    ])
        @slot('tbody')
            @foreach ($datas as $key => $data)
                <tr>
                    <td>
                        <p class="text-xs mb-0 text-center">{{ $datas->firstItem() + $key }}</p>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">{{ $data->profil->nama }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                @if ($data->is_aktif == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">{{ $data->role ?? '' }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <img src="{{ Storage::url('profil/' . $data->profil->foto) }}" class="img-fluid" width="80px"
                                    alt="Responsive image">
                            </div>
                        </div>
                    </td>
                    <td class="text-start">
                        @component('dashboard.components.buttons.edit', [
                            'class' => 'btn-sm',
                            'id' => 'btn-edit',
                            'url' => url('/master-data/akun-lainnya/' . $data->id . '/edit'),
                        ])
                        @endcomponent
                        @component('dashboard.components.buttons.delete', [
                            'class' => 'btn-sm',
                            'id' => 'btn-delete',
                            'value' => $data->id,
                            'url' => 'javascript:void(0)',
                        ])
                        @endcomponent
                    </td>
                </tr>
            @endforeach
        @endslot
    @endcomponent
</div>

@push('script')
    <script>
        $('.select2').on('change', function() {
            @this.set($(this).attr('name'), $(this).select2("val"));
        })
    </script>
@endpush
