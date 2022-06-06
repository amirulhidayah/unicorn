    <table class="table table-bordered table-responsive table-hover text-nowrap">
        <thead class="text-center">
            <tr>
                <th scope="col">No.</th>
                <th scope="col">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                <th scope="col">NO. REK. KEG. SKPD</th>
                <th scope="col">JUMLAH ANGGARAN TAHUN INI</th>
                <th scope="col">TW 1</th>
                <th scope="col">Anggaran Yang Digunakan</th>
                <th scope="col">TW 2</th>
                <th scope="col">Anggaran Yang Digunakan</th>
                <th scope="col">TW 3</th>
                <th scope="col">Anggaran Yang Digunakan</th>
                <th scope="col">TW 4</th>
                <th scope="col">Anggaran Yang Digunakan</th>
            </tr>
        </thead>
        @if (count($daftarBiroOrganisasi) > 0)
            <tbody>
                @foreach ($daftarBiroOrganisasi as $biroOrganisasi)
                    <tr>
                        <td colspan="12" class="fw-bold">{{ $biroOrganisasi->nama }}</td>
                    </tr>
                    @php
                        $daftarProgram = \App\Models\Program::with('kegiatan')
                            ->whereHas('kegiatan', function ($query) use ($biroOrganisasi) {
                                $query->whereHas('spd', function ($query) use ($biroOrganisasi) {
                                    $query->where('biro_organisasi_id', $biroOrganisasi->id);
                                });
                            })
                            ->get();
                    @endphp
                    @foreach ($daftarProgram as $program)
                        <tr>
                            <td colspan="2">{{ $program->nama }}</td>
                            <td colspan="10">{{ $program->no_rek }}</td>
                        </tr>
                        @php
                            $daftarSpd = \App\Models\Spd::with(['biroOrganisasi', 'kegiatan', 'tahun'])
                                ->whereHas('kegiatan', function ($query) use ($program) {
                                    $query->where('program_id', $program->id);
                                })
                                ->where('biro_organisasi_id', $biroOrganisasi->id)
                                ->where('tahun_id', $tahun)
                                ->get();

                            $totalJumlahAnggaran = 0;
                            $totalTw1 = 0;
                            $totalTw2 = 0;
                            $totalTw3 = 0;
                            $totalTw4 = 0;
                            $totalTw5 = 0;
                        @endphp
                        @foreach ($daftarSpd as $spd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $spd->kegiatan->nama }}</td>
                                <td>{{ $spd->kegiatan->no_rek }}</td>
                                <td>Rp.
                                    {{ number_format($spd->tw1 + $spd->tw2 + $spd->tw3 + $spd->tw4, 0, ',', '.') }}
                                </td>
                                <td>Rp. {{ number_format($spd->tw1, 0, ',', '.') }}</td>
                                @php
                                    $sppLs = \App\Models\SppLs::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 1)
                                        ->get();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ')' . '<br>';
                                            @endphp
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($spd->tw2, 0, ',', '.') }}</td>
                                @php
                                    $sppLs = \App\Models\SppLs::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 2)
                                        ->get();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ')' . '<br>';
                                            @endphp
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($spd->tw3, 0, ',', '.') }}</td>
                                @php
                                    $sppLs = \App\Models\SppLs::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 3)
                                        ->get();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ')' . '<br>';
                                            @endphp
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($spd->tw4, 0, ',', '.') }}</td>
                                @php
                                    $sppLs = \App\Models\SppLs::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 4)
                                        ->get();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ')' . '<br>';
                                            @endphp
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @php
                                $totalJumlahAnggaran = $totalJumlahAnggaran + $spd->tw1 + $spd->tw2 + $spd->tw3 + $spd->tw4;
                                $totalTw1 += $spd->tw1;
                                $totalTw2 += $spd->tw2;
                                $totalTw3 += $spd->tw3;
                                $totalTw4 += $spd->tw4;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">Jumlah</td>
                            <td>Rp.
                                {{ number_format($totalJumlahAnggaran, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalTw1, 0, ',', '.') }}
                            </td>
                            <td></td>
                            <td>Rp.
                                {{ number_format($totalTw2, 0, ',', '.') }}
                            </td>
                            <td></td>
                            <td>Rp.
                                {{ number_format($totalTw3, 0, ',', '.') }}
                            </td>
                            <td></td>
                            <td>Rp.
                                {{ number_format($totalTw4, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="12" class="fw-bold text-center">Data Tidak Ada</td>
                </tr>
            </tbody>
        @endif
    </table>
