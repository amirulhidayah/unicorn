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
        </div>
    </div>
    @component('dashboard.components.livewire.table', [
        'th' => [
            [
                'title' => 'No',
                'class' => 'text-center',
            ],
            [
                'title' => 'Nomor Surat Pertanggungjawaban (SPJ)',
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
                    {{-- <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs text-nowrap">
                                    {{ 'Rp. ' . number_format($data->anggaran_digunakan, 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
                    </td> --}}
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
                    <td class="text-center">
                        @php
                            $actionBtn = '';

                            if (($data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id && in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) || Auth::user()->role == 'Admin') {
                                if ($data->status_validasi_asn != 0 && $data->status_validasi_ppk != 0 && ($data->status_validasi_asn == 2 || $data->status_validasi_ppk == 2)) {
                                    $actionBtn .= '<a target="_blank" href="' . Storage::url('surat_penolakan_spj_gu/' . $data->surat_penolakan) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Penolakan</a>';

                                    $actionBtn .= '<a href="' . url('spj-gu/' . $data->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 ml-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                                }
                            }

                            if ($data->status_validasi_akhir == 1) {
                                $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-ls/' . $data->id) . '" class="btn btn-success btn-sm mr-1 my-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                            }

                            if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                                $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('spj-gu/' . $data->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';
                            }

                            if (($data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id && in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && count($data->riwayatSppLs) > 1) || Auth::user()->role == 'Admin') {
                                if ($data->status_validasi_asn == 0 && $data->status_validasi_ppk == 0) {
                                    $actionBtn .= '<a href="' . url('spj-gu/' . $data->id . '/edit') . '" class="btn btn-primary btn-sm my-1 mr-1"><i class="fas fa-pen"></i> Ubah</a></div>';
                                }
                            }

                            if ((in_array(Auth::user()->role, ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id && $data->status_validasi_asn == 0 && $data->status_validasi_ppk == 0) || Auth::user()->role == 'Admin') {
                                $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1 my-1" value="' . $data->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                            }

                            if (Auth::user()->role == 'PPK') {
                                if ($data->status_validasi_ppk == 1 && $data->status_validasi_akhir == 0 && $data->status_validasi_asn == 1 && Auth::user()->is_aktif == 1) {
                                    $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1 my-1" value="' . $data->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                                }
                            }

                            if (in_array(Auth::user()->role, ['ASN Sub Bagian Keuangan', 'PPK']) && $data->status_validasi_akhir == 0 && Auth::user()->is_aktif == 1) {
                                $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 my-1" href="' . url('spj-gu/' . $data->id) . '"><i class="far fa-check-circle"></i> Proses Validasi</a>';
                            }

                            $actionBtn .= '<a href="' . url('spj-gu/riwayat/' . $data->id) . '" class="btn btn-primary btn-sm my-1"><i class="fas fa-history"></i> Riwayat</a>';

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
        $('#status_upload_skm').change(function() {
            if ($(this).val() != 'Semua') {
                $("#status_verifikasi_asn").val('Semua').trigger('change');
                $("#status_verifikasi_ppk").val('Semua').trigger('change');
                $("#status_verifikasi_akhir").val('Semua').trigger('change');
            }
        })

        $('#status_upload_arsip_sp2d').change(function() {
            if ($(this).val() != 'Semua') {
                $("#status_verifikasi_asn").val('Semua').trigger('change');
                $("#status_verifikasi_ppk").val('Semua').trigger('change');
                $("#status_verifikasi_akhir").val('Semua').trigger('change');
                $('#status_upload_skm').val('Semua').trigger('change');
            }
        })

        $('.select2-filter').on('change', function() {
            @this.set($(this).attr('name'), $(this).select2("val"));
        })
    </script>
@endpush