<div>
    <div>
        <div class="row mb-3" wire:ignore>
            <div class="col-sm-12 col-lg-6">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Sekretariat Daerah',
                    'id' => 'sekretariat_daerah',
                    'name' => 'sekretariat_daerah',
                    'class' => 'select2',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">Semua</option>
                        @foreach ($daftarSekretariatDaerah as $sekretariatDaerah)
                            <option value="{{ $sekretariatDaerah->id }}">{{ $sekretariatDaerah->nama }}</option>
                        @endforeach
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
                        <option value="Bendahara Pengeluaran">Bendahara Pengeluaran</option>
                        <option value="Bendahara Pengeluaran Pembantu">Bendahara Pengeluaran Pembantu</option>
                        <option value="Bendahara Pengeluaran Pembantu Belanja Hibah">Bendahara Pengeluaran Pembantu Belanja Hibah
                        </option>
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
                'title' => 'Username',
            ],
            [
                'title' => 'Sekretariat Daerah',
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
                                <h6 class="mb-0 text-xs">{{ $data->username }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">{{ $data->profil->sekretariatDaerah->nama ?? '' }}</h6>
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
                            'url' => url('/master-data/akun/' . $data->id . '/edit'),
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
