@push('style')
    <style>
        .table thead th {
            padding: 0.75rem 0rem;
        }

        .table> :not(caption)>*>* {
            padding: 0.5rem 0rem;
        }

        .table th,
        .table td {
            padding-top: 15px !important;
            padding-bottom: 15px !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    </style>
@endpush

<div class="row mx-2">
    <div class="col-auto mt-2">
        <div class="form-row align-items-center ml-3 justify-content-center float-md-left mr-auto">
            <div class="col-auto my-1">
                <label for="" class="mb-0">Jumlah Data</label>
            </div>
            <div class="col-auto my-1" wire:ignore>
                <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" wire:model='totalPagination'>
                    <option selected value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col mt-2">
        <div class="form-row align-items-center mr-3 justify-content-center float-md-right ml-auto">
            <div class="col-auto my-1">
                <label for="" class="mb-0">Cari</label>
            </div>
            <div class="col-auto my-1">
                <input type="text" class="form-control" placeholder="{{ $placeholderSearch ?? 'Cari' }}"
                    wire:model.debounce.500ms='cari'>
            </div>
            <div class="col-auto my-1">
                @if (isset($export) && $export)
                    @component('dashboard.components.buttons.custom', [
                        'icon' => '<i class="fas fa-file-export"></i>',
                        'id' => 'toggle-on',
                        'class' => 'btn-success',
                        'label' => 'Export',
                        'wireClick' => 'export',
                    ])
                    @endcomponent
                @endif
            </div>

        </div>
    </div>
</div>

<div class="table-responsive my-0 mx-0">
    <table class="table table-hover align-items-center mb-0">
        <thead>
            <tr>
                @foreach ($th as $index => $item)
                    <th scope="col" class="{{ $item['class'] ?? '' }} text-uppercase  mx-0">
                        {{ $item['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="list">
            @if (count($datas) > 0)
                {!! $tbody ?? '' !!}
            @else
                <tr>
                    <td colspan="{{ count($th) }}" style="font-size: 12px" class="text-center"><span
                            class="badge bg-gradient-danger my-3">Data Tidak Ada</span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    @if (count($datas) > 0)
        <div class="text-center mx-1 mb-2 d-flex justify-content-between row align-items-center border-top pt-4">
            <div class="align-middle pl-3 col-md-auto mb-2 col-sm-12">
                <label class="mb-0 text-subtitle">Menampilkan
                    {{ ($datas->currentpage() - 1) * $datas->perpage() + 1 }}
                    -
                    {{ $datas->currentpage() * $datas->perpage() }}
                    dari {{ $datas->total() }} data
                </label>
            </div>
            <div class="pr-4 col-md-auto col-sm-12 mb-2">
                {{ $datas->links() }}
            </div>
        </div>
    @endif

</div>
