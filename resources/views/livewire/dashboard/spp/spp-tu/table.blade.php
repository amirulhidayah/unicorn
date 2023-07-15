<div>
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
                'title' => 'Jumlah Anggaran',
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
                                <h6 class="mb-0 text-xs">{{ $data->kegiatanSpp->nama . ' (' . $data->kegiatanSpp->no_rek . ')' }}
                                </h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">
                                    {{ $data->kegiatanSpp->programSpp->nama . ' (' . $data->kegiatanSpp->programSpp->no_rek . ')' }}
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
                                    {{ 'Rp. ' . number_format($data->jumlah_anggaran, 0, ',', '.') }}
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
                                    $actionBtn .= '<div class="d-flex justify-content-center mb-1"><a href="' . url('/surat-penolakan/spp-tu/' . $data->id . '/' . $data->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-envelope"></i> Surat Pengembalian</a>';

                                    $actionBtn .= '<a href="' . url('spp-tu/' . $data->id . '/edit') . '" class="btn btn-primary btn-sm mt-1 mr-1"><i class="fas fa-file-pdf"></i> Perbaiki</a></div>';
                                }
                            }

                            if ($data->status_validasi_akhir == 1) {
                                $actionBtn .= '<a href="' . url('/surat-pernyataan/spp-tu/' . $data->id) . '" class="btn btn-success btn-sm mr-1"><i class="fas fa-envelope"></i> Surat Pernyataan</a>';
                            }

                            if (in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                                $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $data->id) . '"><i class="far fa-check-circle"></i> Lihat</a>';

                                if (($data->status_validasi_akhir == 0 && in_array(Auth::user()->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah']) && $data->sekretariat_daerah_id == Auth::user()->profil->sekretariat_daerah_id) || Auth::user()->role == 'Admin') {
                                    $actionBtn .= '<button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $data->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                                }
                            }

                            if (Auth::user()->role == 'ASN Sub Bagian Keuangan') {
                                if (Auth::user()->role == 'ASN Sub Bagian Keuangan' && $data->status_validasi_asn == 0 && Auth::user()->is_aktif == 1) {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $data->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                                } else {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1 " href="' . url('spp-tu/' . $data->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                                }
                            }

                            if (Auth::user()->role == 'PPK') {
                                if ($data->status_validasi_ppk == 0 && Auth::user()->is_aktif == 1) {
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $data->id) . '"><i class="far fa-check-circle"></i> Proses</a>';
                                } else {
                                    if ($data->status_validasi_ppk == 1 && $data->status_validasi_akhir == 0 && $data->status_validasi_asn == 1 && Auth::user()->is_aktif == 1) {
                                        $actionBtn .= '<button id="btn-verifikasi" class="btn btn-success btn-sm mr-1" value="' . $data->id . '" > <i class="far fa-check-circle"></i> Selesai</button>';
                                    }
                                    $actionBtn .= '<a class="btn btn-primary text-light btn-sm mr-1" href="' . url('spp-tu/' . $data->id) . '"><i class="fas fa-eye"></i> Lihat</a>';
                                }
                            }

                            $actionBtn .= '<a href="' . url('spp-tu/riwayat/' . $data->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-history"></i> Riwayat</a>';

                        @endphp
                        {!! $actionBtn !!}
                    </td>
                </tr>
            @endforeach
        @endslot
    @endcomponent
</div>
