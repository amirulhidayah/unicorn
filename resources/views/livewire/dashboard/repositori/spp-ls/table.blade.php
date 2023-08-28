<div>
    <div>
        <div class="row mb-3" wire:ignore>
            @if (in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']))
                <div class="col-md-4 col-sm-12">
                    @component('dashboard.components.formElements.select', [
                        'label' => 'Sekretariat Daerah',
                        'id' => 'sekretariat_daerah',
                        'name' => 'sekretariat_daerah',
                        'class' => 'select2 select2-filter',
                        'wajib' => '<sup class="text-danger">*</sup>',
                    ])
                        @slot('options')
                            <option value="Semua">
                                Semua
                            </option>
                            @foreach ($daftarSekretariatDaerah as $sekretariatDaerah)
                                <option value="{{ $sekretariatDaerah->id }}">
                                    {{ $sekretariatDaerah->nama }}
                                </option>
                            @endforeach
                        @endslot
                    @endcomponent
                </div>
            @endif
            <div
                class="{{ in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? 'col-md-4' : 'col-md-6' }} col-sm-12">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Bulan',
                    'id' => 'bulan',
                    'name' => 'bulan',
                    'class' => 'select2 select2-filter',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">
                            Semua
                        </option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    @endslot
                @endcomponent
            </div>
            <div
                class="{{ in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? 'col-md-4' : 'col-md-6' }} col-sm-12">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Tahun',
                    'id' => 'tahun',
                    'name' => 'tahun',
                    'class' => 'select2 select2-filter',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">
                            Semua
                        </option>
                        @foreach ($daftarTahun as $tahun)
                            <option value="{{ $tahun->id }}">
                                {{ $tahun->tahun }}
                            </option>
                        @endforeach
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-12 col-sm-12">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Kategori',
                    'id' => 'kategori',
                    'name' => 'kategori',
                    'class' => 'select2 select2-filter',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        @foreach ($daftarKategori as $kategori)
                            <option value="{{ $kategori->id }}">
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
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
                'title' => 'Nomor Surat Permintaan Pembayaran (SPP)',
            ],
            [
                'title' => 'Tanggal',
            ],
            [
                'title' => 'Sekretariat Daerah',
            ],
            [
                'title' => 'Kategori',
            ],
            [
                'title' => 'Periode',
            ],
            [
                'title' => 'Kegiatan',
            ],
            [
                'title' => 'SPM',
                'class' => 'text-center',
            ],
            [
                'title' => 'Arsip SP2D',
                'class' => 'text-center',
            ],
            [
                'title' => 'Aksi',
                'class' => 'text-center',
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
                                <h6 class="mb-0 text-xs">
                                    {{ $data->nomor_surat }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->sekretariatDaerah->nama }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->kategoriSppLs->nama }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-nowrap">
                            {{ $data->bulan . ', ' . $data->tahun->tahun }}
                        </h6>
                    </td>
                    <td class="text-nowrap">
                        @foreach ($data->kegiatanSppLs as $kegiatanSppLs)
                            <h6 class="mb-0 text-xs text-nowrap my-2">
                                - {{ $kegiatanSppLs->kegiatan->nama . ' (' . $kegiatanSppLs->kegiatan->no_rek . ')' }}
                                <br>
                                {{ 'Rp.' . number_format($kegiatanSppLs->anggaran_digunakan ?? 0, 0, ',', '.') }}
                            </h6>
                        @endforeach
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            <button
                                onclick="openPdfInFullscreen('{{ Storage::url('dokumen_spm_spp_ls/' . $data->dokumen_spm) }}')"
                                target="_blank" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i>
                                SPM</button>
                        </h6>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            <button
                                onclick="openPdfInFullscreen('{{ Storage::url('dokumen_arsip_sp2d_spp_ls/' . $data->dokumen_arsip_sp2d) }}')"
                                target="_blank" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Arsip
                                SP2D</button>
                        </h6>
                    </td>
                    <td class="text-center">
                        @php
                            $actionBtn = '';
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('repositori/spp-ls/download-semua-berkas/' . $data->id) . '"><i class="fas fa-download"></i> Download Semua Berkas</a>';
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('repositori/spp-ls/' . $data->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';
                        @endphp
                        {!! $actionBtn !!}
                    </td>
                </tr>
            @endforeach
        @endslot
    @endcomponent
</div>

@push('script')
    <script>
        $('.select2-filter').on('change', function() {
            @this.set($(this).attr('name'), $(this).select2("val"));
        })
    </script>
@endpush
