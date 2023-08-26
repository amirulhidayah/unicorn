<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">Detail SPP TU</div>
                <div class="card-tools">
                    @if (in_array(Auth::user()->role, ['PPK', 'ASN Sub Bagian Keuangan']) && $sppTu->status_validasi_akhir == 0)
                        @component('dashboard.components.buttons.verifikasi', [
                            'id' => 'btn-verifikasi',
                            'class' => '',
                            'type' => 'button',
                        ])
                        @endcomponent
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Nomor Surat Permintaan Pembayaran (SPP)',
                        'isi' => $sppTu->nomor_surat,
                    ])
                    @endcomponent
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Sekretariat Daerah',
                        'isi' => $sppTu->sekretariatDaerah->nama,
                    ])
                    @endcomponent
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Tahun',
                        'isi' => $sppTu->tahun->tahun,
                    ])
                    @endcomponent
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Bulan',
                        'isi' => $sppTu->bulan,
                    ])
                    @endcomponent
                    <div class="col-12 mb-4">
                        <p class="h4 my-3 fw-bold">Program dan Kegiatan</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Program</th>
                                    <th scope="col">Kegiatan</th>
                                    <th scope="col">Jumlah Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programDanKegiatan as $kegiatanSppLs)
                                    <tr>
                                        <td>{{ $kegiatanSppLs['program'] }}
                                        </td>
                                        <td>{{ $kegiatanSppLs['kegiatan'] }}
                                        </td>
                                        <td>{{ number_format($kegiatanSppLs['jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="fw-bold text-center">Total</td>
                                    <td>{{ number_format($totalProgramDanKegiatan['total_jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mb-4">
                        <p class="h4 my-3 fw-bold">Dokumen</p>
                        @component('dashboard.components.widgets.listDokumen', [
                            'dokumenSpp' => $sppTu->dokumenSppTu,
                            'spp' => $sppTu,
                            'tipe' => $tipe,
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
