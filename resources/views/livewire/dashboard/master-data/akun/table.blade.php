<div>
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
                                <h6 class="mb-0 text-xs">{{ $data->profil->SekretariatDaerah->nama ?? '' }}</h6>
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
