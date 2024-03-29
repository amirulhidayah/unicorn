<div class="row mb-4 mx-1">

    @if (in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']))
        <div class="col">
            @component('dashboard.components.formElements.select', [
                'label' => 'Sekretariat Daerah',
                'id' => 'sekretariat_daerah',
                'name' => 'sekretariat_daerah',
                'class' => 'select2 filter',
                'wajib' => '<sup class="text-danger">*</sup>',
            ])
                @slot('options')
                    <option value="Semua">
                        Semua
                    </option>
                    @foreach ($daftarSekretariatDaerah as $SekretariatDaerah)
                        <option value="{{ $SekretariatDaerah->id }}">
                            {{ $SekretariatDaerah->nama }}
                        </option>
                    @endforeach
                @endslot
            @endcomponent
        </div>
    @endif
    <div class="col">
        @component('dashboard.components.formElements.select', [
            'label' => 'Status Verifikasi',
            'id' => 'status',
            'name' => 'status',
            'class' => 'select2 filter',
            'wajib' => '<sup class="text-danger">*</sup>',
        ])
            @slot('options')
                <option value="Semua">
                    Semua
                </option>
                <option value="Belum Diproses">
                    Belum Diproses
                </option>
                <option value="Ditolak">
                    Ditolak
                </option>
                <option value="Selesai">
                    Selesai
                </option>
            @endslot
        @endcomponent
    </div>
    <div class="col">
        @component('dashboard.components.formElements.select', [
            'label' => 'Tahun',
            'id' => 'tahun',
            'name' => 'tahun',
            'class' => 'select2 filter',
            'wajib' => '<sup class="text-danger">*</sup>',
        ])
            @slot('options')
                <option value="Semua">
                    Semua
                </option>
                @foreach ($daftarTahun as $tahun)
                    <option value="{{ $tahun->id }}">{{ $tahun->tahun }}</option>
                @endforeach
            @endslot
        @endcomponent
    </div>

</div>

@push('script')
    <script>
        $('.filter').on('change', function() {
            Livewire.emit('refreshTable');
        })
    </script>
@endpush
