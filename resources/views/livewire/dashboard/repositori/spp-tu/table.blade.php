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
            @if (Auth::user()->role != 'Operator SPM')
                <div class="col-md-4 col-sm-12">
                    @component('dashboard.components.formElements.select', [
                        'label' => 'Status Verifikasi ASN Sub Bagian Keuangan',
                        'id' => 'status_verifikasi_asn',
                        'name' => 'status_verifikasi_asn',
                        'class' => 'select2 select2-filter',
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
                            <option value="Diterima">
                                Diterima
                            </option>
                        @endslot
                    @endcomponent
                </div>
                <div class="col-md-4 col-sm-12">
                    @component('dashboard.components.formElements.select', [
                        'label' => 'Status Verifikasi PPK',
                        'id' => 'status_verifikasi_ppk',
                        'name' => 'status_verifikasi_ppk',
                        'class' => 'select2 select2-filter',
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
                            <option value="Diterima">
                                Diterima
                            </option>
                        @endslot
                    @endcomponent
                </div>
                <div class="col-md-4 col-sm-12">
                    @component('dashboard.components.formElements.select', [
                        'label' => 'Status Verifikasi Akhir',
                        'id' => 'status_verifikasi_akhir',
                        'name' => 'status_verifikasi_akhir',
                        'class' => 'select2 select2-filter',
                        'wajib' => '<sup class="text-danger">*</sup>',
                    ])
                        @slot('options')
                            <option value="Semua">
                                Semua
                            </option>
                            <option value="Belum Diproses">
                                Belum Diproses
                            </option>
                            <option value="Diverifikasi">
                                Diverifikasi
                            </option>
                        @endslot
                    @endcomponent
                </div>
            @endif
            <div class="col-md-6 col-sm-12">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Status Upload SPM',
                    'id' => 'status_upload_skm',
                    'name' => 'status_upload_skm',
                    'class' => 'select2 select2-filter',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">Semua</option>
                        <option value="Belum Ada">Belum Ada</option>
                        <option value="Sudah Ada">Sudah Ada</option>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6 col-sm-12">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Status Upload Arsip SP2D',
                    'id' => 'status_upload_arsip_sp2d',
                    'name' => 'status_upload_arsip_sp2d',
                    'class' => 'select2 select2-filter',
                    'wajib' => '<sup class="text-danger">*</sup>',
                ])
                    @slot('options')
                        <option value="Semua">Semua</option>
                        <option value="Belum Ada">Belum Ada</option>
                        <option value="Sudah Ada">Sudah Ada</option>
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
                'title' => 'Periode',
            ],
            [
                'title' => 'Kegiatan',
            ],
            [
                'title' => 'Verifikasi ASN Sub Bagian Keuangan',
                'class' => 'text-center',
            ],
            [
                'title' => 'Verifikasi PPK',
                'class' => 'text-center',
            ],
            [
                'title' => 'Status Verifikasi Akhir',
                'class' => 'text-center',
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
                        <h6 class="mb-0 text-xs text-nowrap">
                            {{ $data->bulan . ', ' . $data->tahun->tahun }}
                        </h6>
                    </td>
                    <td class="text-nowrap">
                        @foreach ($data->kegiatanSppTu as $kegiatanSppTu)
                            <h6 class="mb-0 text-xs text-nowrap my-2">
                                - {{ $kegiatanSppTu->kegiatan->nama . ' (' . $kegiatanSppTu->kegiatan->no_rek . ')' }}
                                <br>
                                {{ 'Rp.' . number_format($kegiatanSppTu->jumlah_anggaran ?? 0, 0, ',', '.') }}
                            </h6>
                        @endforeach
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            @if ($data->status_validasi_asn == 0)
                                <span class="badge badge-primary text-light">Belum Di Proses</span>
                            @elseif ($data->status_validasi_asn == 1)
                                <span class="badge badge-success">Diterima</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </h6>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            @if ($data->status_validasi_ppk == 0)
                                <span class="badge badge-primary text-light">Belum Di Proses</span>
                            @elseif ($data->status_validasi_ppk == 1)
                                <span class="badge badge-success">Diterima</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </h6>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            @if ($data->status_validasi_akhir == 0)
                                <span class="badge badge-primary text-light">Belum Di Proses</span>
                            @else
                                <span class="badge badge-success">Diverifikasi</span>
                            @endif
                        </h6>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            @if (!$data->dokumen_spm)
                                <span class="badge badge-primary text-light">Belum Ada</span>
                            @else
                                <button
                                    onclick="openPdfInFullscreen('{{ Storage::url('dokumen_spm_spp_tu/' . $data->dokumen_spm) }}')"
                                    target="_blank" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i>
                                    SPM</button>
                            @endif

                            @if (in_array(Auth::user()->role, ['Admin', 'Operator SPM']) &&
                                    $data->status_validasi_ppk == 1 &&
                                    $data->status_validasi_asn == 1 &&
                                    $data->status_validasi_akhir == 1)
                                <button id="btn-upload-spm" class="btn btn-primary btn-sm mr-1 my-1"
                                    value="{{ $data->id }}">

                                    {!! !$data->dokumen_spm
                                        ? '<i class="fas fa-upload"></i> Upload SPM'
                                        : '<i class="fas fa-pen mr-1"></i> Ubah SPM' !!}</button>
                            @endif
                        </h6>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-center">
                            @if (!$data->dokumen_arsip_sp2d)
                                <span class="badge badge-primary text-light">Belum Ada</span>
                            @else
                                <button
                                    onclick="openPdfInFullscreen('{{ Storage::url('dokumen_arsip_sp2d_spp_tu/' . $data->dokumen_arsip_sp2d) }}')"
                                    target="_blank" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Arsip
                                    SP2D</button>
                            @endif

                            @if (in_array(Auth::user()->role, [
                                    'Admin',
                                    'Bendahara Pengeluaran',
                                    'Bendahara Pengeluaran Pembantu',
                                    'Bendahara Pengeluaran Pembantu Belanja Hibah',
                                ]) &&
                                    $data->status_validasi_ppk == 1 &&
                                    $data->status_validasi_asn == 1 &&
                                    $data->status_validasi_akhir == 1 &&
                                    $data->dokumen_spm)
                                <button id="btn-upload-sp2d" class="btn btn-primary btn-sm mr-1 my-1"
                                    value="{{ $data->id }}">
                                    {!! !$data->dokumen_arsip_sp2d
                                        ? '<i class="fas fa-upload"></i> Upload Arsip SP2D'
                                        : '<i class="fas fa-pen mr-1"></i> Ubah Arsip SP2D' !!}</button>
                            @endif
                        </h6>
                    </td>
                    <td class="text-center">
                        @php
                            $actionBtn = '';
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('repositori/spp-tu/download-semua-berkas/' . $data->id) . '"><i class="fas fa-download"></i> Download Semua Berkas</a>';
                            $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('repositori/spp-tu/' . $data->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                        @endphp
                        {!! $actionBtn !!}
                    </td>
                </tr>
            @endforeach
        @endslot
    @endcomponent
</div>

@push('script')
    $('.select2-filter').on('change', function() {
    @this.set($(this).attr('name'), $(this).select2("val"));
    })
    </script>
@endpush
