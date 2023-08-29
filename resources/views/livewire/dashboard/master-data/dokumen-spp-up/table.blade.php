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
                'title' => 'Aksi',
            ],
        ],
        'datas' => $datas,
    ])
        @slot('tbody')
            @foreach ($datas as $key => $data)
                <tr wire:key="dokumen-spp-up-{{ Str::random() }}">
                    <td>
                        <p class="text-xs mb-0 text-center">{{ $datas->firstItem() + $key }}</p>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">{{ $data->nama }}</h6>
                            </div>
                        </div>
                    </td>
                    <td class="text-start">
                        @component('dashboard.components.buttons.edit', [
                            'class' => 'btn-sm',
                            'id' => 'btn-edit',
                            'value' => $data->id,
                            'url' => 'javascript:void(0)',
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
