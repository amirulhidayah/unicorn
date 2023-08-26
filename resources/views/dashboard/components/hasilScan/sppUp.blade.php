<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">Detail SPP UP</div>
                <div class="card-tools">
                    @if (in_array(Auth::user()->role, ['PPK', 'ASN Sub Bagian Keuangan']) && $sppUp->status_validasi_akhir == 0)
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
                        'isi' => $sppUp->nomor_surat,
                    ])
                    @endcomponent
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Sekretariat Daerah',
                        'isi' => $sppUp->sekretariatDaerah->nama,
                    ])
                    @endcomponent
                    @component('dashboard.components.widgets.info', [
                        'judul' => 'Tahun',
                        'isi' => $sppUp->tahun->tahun,
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
                                @foreach ($programDanKegiatan as $kegiatanSppUp)
                                    <tr>
                                        <td>{{ $kegiatanSppUp['program'] }}
                                        </td>
                                        <td>{{ $kegiatanSppUp['kegiatan'] }}
                                        </td>
                                        <td>{{ number_format($kegiatanSppUp['jumlah_anggaran'] ?? 0, 0, ',', '.') }}
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
                            'dokumenSpp' => $sppUp->dokumenSppUp,
                            'spp' => $sppUp,
                            'tipe' => $tipe,
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
