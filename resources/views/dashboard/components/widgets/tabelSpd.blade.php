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
                @if (Auth::user()->role == 'Admin')
                    <th scope="col">Aksi</th>
                @endif
            </tr>
        </thead>
        @if (count($daftarBiroOrganisasi) > 0)
            <tbody>
                @foreach ($daftarBiroOrganisasi as $biroOrganisasi)
                    <tr>
                        <td colspan="{{ Auth::user()->role == 'Admin' ? 13 : 12 }}" class="fw-bold">
                            {{ $biroOrganisasi->nama }}</td>
                    </tr>
                    @php
                        $daftarProgram = \App\Models\Program::with('kegiatan')
                            ->whereHas('kegiatan', function ($query) use ($biroOrganisasi, $tahun) {
                                $query->whereHas('spd', function ($query) use ($biroOrganisasi, $tahun) {
                                    $query->where('biro_organisasi_id', $biroOrganisasi->id);
                                    if ($tahun) {
                                        $query->where('tahun_id', $tahun);
                                    }
                                });
                            })
                            ->withTrashed()
                            ->get();
                    @endphp
                    @foreach ($daftarProgram as $program)
                        <tr>
                            <td colspan="2">{{ $program->nama }}</td>
                            <td colspan="{{ Auth::user()->role == 'Admin' ? 11 : 10 }}">{{ $program->no_rek }}</td>
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

                            $totalAnggaranDigunakanTw1 = 0;
                            $totalAnggaranDigunakanTw2 = 0;
                            $totalAnggaranDigunakanTw3 = 0;
                            $totalAnggaranDigunakanTw4 = 0;
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

                                    $sppGu = \App\Models\SppGu::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 1)
                                        ->first();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ', SPP-LS)' . '<br>';
                                                $totalAnggaranDigunakanTw1 += $item->anggaran_digunakan;
                                            @endphp
                                        @endforeach
                                    @endif

                                    @if ($sppGu)
                                        @php
                                            $anggaranDigunakan = $sppGu ? 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.') : '-';
                                            echo $anggaranDigunakan . ' (SPP-GU)' . '<br>';
                                            $totalAnggaranDigunakanTw1 += $sppGu->anggaran_digunakan;
                                        @endphp
                                    @endif

                                    @if (!$sppGu && count($sppLs) == 0)
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

                                    $sppGu = \App\Models\SppGu::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 2)
                                        ->first();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ', SPP-LS)' . '<br>';
                                                $totalAnggaranDigunakanTw2 += $item->anggaran_digunakan;
                                            @endphp
                                        @endforeach
                                    @endif

                                    @if ($sppGu)
                                        @php
                                            $anggaranDigunakan = $sppGu ? 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.') : '-';
                                            echo $anggaranDigunakan . ' (SPP-GU)' . '<br>';
                                            $totalAnggaranDigunakanTw2 += $sppGu->anggaran_digunakan;
                                        @endphp
                                    @endif

                                    @if (!$sppGu && count($sppLs) == 0)
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

                                    $sppGu = \App\Models\SppGu::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 3)
                                        ->first();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ', SPP-LS)' . '<br>';
                                                $totalAnggaranDigunakanTw3 += $item->anggaran_digunakan;
                                            @endphp
                                        @endforeach
                                    @endif

                                    @if ($sppGu)
                                        @php
                                            $anggaranDigunakan = $sppGu ? 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.') : '-';
                                            echo $anggaranDigunakan . ' (SPP-GU)' . '<br>';
                                            $totalAnggaranDigunakanTw3 += $sppGu->anggaran_digunakan;
                                        @endphp
                                    @endif

                                    @if (!$sppGu && count($sppLs) == 0)
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

                                    $sppGu = \App\Models\SppGu::where('biro_organisasi_id', $spd->biro_organisasi_id)
                                        ->orderBy('created_at', 'asc')
                                        ->where('tahun_id', $spd->tahun_id)
                                        ->where('kegiatan_id', $spd->kegiatan_id)
                                        ->where('status_validasi_akhir', 1)
                                        ->where('tw', 4)
                                        ->first();
                                @endphp
                                <td>
                                    @if (count($sppLs) > 0)
                                        @foreach ($sppLs as $item)
                                            @php
                                                $anggaranDigunakan = $sppLs ? 'Rp. ' . number_format($item->anggaran_digunakan, 0, ',', '.') : '-';
                                                echo $anggaranDigunakan . ' (' . $item->kategori . ', SPP-LS)' . '<br>';
                                                $totalAnggaranDigunakanTw4 += $item->anggaran_digunakan;
                                            @endphp
                                        @endforeach
                                    @endif

                                    @if ($sppGu)
                                        @php
                                            $anggaranDigunakan = $sppGu ? 'Rp. ' . number_format($sppGu->anggaran_digunakan, 0, ',', '.') : '-';
                                            echo $anggaranDigunakan . ' (SPP-GU)' . '<br>';
                                            $totalAnggaranDigunakanTw4 += $sppGu->anggaran_digunakan;
                                        @endphp
                                    @endif

                                    @if (!$sppGu && count($sppLs) == 0)
                                        -
                                    @endif
                                </td>
                                @if (Auth::user()->role == 'Admin')
                                    <td>
                                        <button id="btn-edit" class="btn btn-warning btn-sm mr-1"
                                            value="{{ $spd->id }}"><i class="fas fa-edit"></i> Ubah</button>
                                        <button id="btn-delete" class="btn btn-danger btn-sm mr-1"
                                            value="{{ $spd->id }}"> <i class="fas fa-trash-alt"></i> Hapus</button>
                                    </td>
                                @endif

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
                            <td>Rp.
                                {{ number_format($totalAnggaranDigunakanTw1, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalTw2, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalAnggaranDigunakanTw2, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalTw3, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalAnggaranDigunakanTw3, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalTw4, 0, ',', '.') }}
                            </td>
                            <td>Rp.
                                {{ number_format($totalAnggaranDigunakanTw4, 0, ',', '.') }}
                            </td>
                            @if (Auth::user()->role == 'Admin')
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="{{ Auth::user()->role == 'Admin' ? 13 : 12 }}" class="fw-bold text-center">Data
                        Tidak Ada</td>
                </tr>
            </tbody>
        @endif
    </table>
