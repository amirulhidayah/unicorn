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
            <div class="col-md-4 col-sm-12">
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
                class="{{ in_array(Auth::user()->role, ['Admin', 'PPK', 'ASN Sub Bagian Keuangan', 'Kuasa Pengguna Anggaran']) ? 'col-md-4' : 'col-md-12' }} col-sm-12">
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
        </div>
    </div>
    @component('dashboard.components.livewire.table', [
        'th' => [
            [
                'title' => 'No',
                'class' => 'text-center',
            ],
            [
                'title' => 'Tanggal',
            ],
            [
                'title' => 'Kegiatan',
            ],
            [
                'title' => 'Program',
            ],
            [
                'title' => 'Sekretariat Daerah',
            ],
            [
                'title' => 'Periode',
            ],
            [
                'title' => 'Anggaran Digunakan',
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
                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->kegiatanDpa->nama . ' (' . $data->kegiatanDpa->no_rek . ')' }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->kegiatanDpa->programDpa->nama . ' (' . $data->kegiatanDpa->programDpa->no_rek . ')' }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->SekretariatDaerah->nama }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h6 class="mb-0 text-xs text-nowrap">
                            {{ $data->bulan . ', ' . $data->tahun->tahun }}
                        </h6>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs text-nowrap">
                                    {{ 'Rp. ' . number_format($data->anggaran_digunakan, 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
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
                    <td class="text-center text-nowrap">
                        @php
                            $actionBtn = '';
                            
                            if ($data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id || in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                                if ($data->status_validasi_asn != 0 && $data->status_validasi_ppk != 0 && ($data->status_validasi_asn == 2 || $data->status_validasi_ppk == 2)) {
                                    $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-ls/' . $data->id . '/' . $data->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';
                            
                                    $actionBtn .= '<a href="' . url('spp-ls/' . $data->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                                }
                            }
                            
                            if ($data->status_validasi_akhir == 1) {
                                $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-ls/' . $data->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                            }
                            
                            if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                                $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $data->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';
                            
                                if (($data->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == 'Admin') {
                                    $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $data->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                                }
                            }
                            
                            if (Auth::user()->role == 'ASN Sub Bagian Keuangan') {
                                if (Auth::user()->role == 'ASN Sub Bagian Keuangan' && $data->status_validasi_asn == 0 && Auth::user()->is_aktif == 1) {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $data->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                                } else {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-ls/' . $data->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                                }
                            }
                            
                            if (Auth::user()->role == 'PPK') {
                                if ($data->status_validasi_ppk == 0 && Auth::user()->is_aktif == 1) {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $data->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                                } else {
                                    if ($data->status_validasi_ppk == 1 && $data->status_validasi_akhir == 0 && $data->status_validasi_asn == 1 && Auth::user()->is_aktif == 1) {
                                        $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $data->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                                    }
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-ls/' . $data->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                                }
                            }
                            
                            $actionBtn .= '<a href="' . url('spp-ls/riwayat/' . $data->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';
                            
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
