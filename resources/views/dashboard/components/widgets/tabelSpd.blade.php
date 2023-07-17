    <table class="table table-bordered table-responsive table-hover text-nowrap">
        <thead class="text-center">
            <tr>
                <th scope="col" rowspan="2">No.</th>
                <th scope="col" rowspan="2">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                <th scope="col" rowspan="2">NO. REK. KEG. SKPD</th>
                <th scope="col" rowspan="2">Jumlah Anggaran</th>
                <th scope="col" colspan="2">Januari</th>
                <th scope="col" colspan="2">Februari</th>
                <th scope="col" colspan="2">Maret</th>
                <th scope="col" colspan="2">April</th>
                <th scope="col" colspan="2">Mei</th>
                <th scope="col" colspan="2">Juni</th>
                <th scope="col" colspan="2">Juli</th>
                <th scope="col" colspan="2">Agustus</th>
                <th scope="col" colspan="2">September</th>
                <th scope="col" colspan="2">Oktober</th>
                <th scope="col" colspan="2">November</th>
                <th scope="col" colspan="2">Desember</th>

                @if (Auth::user()->role == 'Admin')
                    <th scope="col">Aksi</th>
                @endif
            </tr>
            <tr>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
                @if (Auth::user()->role == 'Admin')
                    <td></td>
                @endif
            </tr>
        </thead>
        @if (count($daftarSekretariatDaerah) > 0)
            <tbody>
                @foreach ($daftarSekretariatDaerah as $SekretariatDaerah)
                    <tr>
                        <td colspan="{{ Auth::user()->role == 'Admin' ? 28 : 28 }}" class="fw-bold">
                            {{ $SekretariatDaerah->nama }}</td>
                    </tr>
                    @php
                        $daftarProgram = \App\Models\ProgramDpa::with('kegiatanDpa')
                            ->whereHas('kegiatanDpa', function ($query) use ($SekretariatDaerah, $tahun) {
                                $query->whereHas('spd', function ($query) use ($SekretariatDaerah, $tahun) {
                                    $query->where('sekretariat_daerah_id', $SekretariatDaerah->id);
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
                            <td colspan="{{ Auth::user()->role == 'Admin' ? 27 : 26 }}">{{ $program->no_rek }}</td>
                        </tr>
                        @php
                            $daftarSpd = \App\Models\Spd::with(['SekretariatDaerah', 'kegiatanDpa', 'tahun'])
                                ->whereHas('kegiatanDpa', function ($query) use ($program) {
                                    $query->where('program_dpa_id', $program->id);
                                })
                                ->where('sekretariat_daerah_id', $SekretariatDaerah->id)
                                ->where('tahun_id', $tahun)
                                ->get();

                            $totalJumlahAnggaran = 0;
                        @endphp
                        @foreach ($daftarSpd as $spd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $spd->kegiatanDpa->nama }}</td>
                                <td>{{ $spd->kegiatanDpa->no_rek }}</td>
                                <td>Rp.
                                    {{ number_format($spd->jumlah_anggaran, 0, ',', '.') }}
                                </td>
                                @php
                                    $totalJumlahAnggaran = $spd->jumlah_anggaran + $totalJumlahAnggaran;
                                @endphp
                                {{-- Januari --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Januari')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Januari')
                                            ->sum('anggaran_digunakan');

                                        $totalJanuari = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalJanuari, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranJanuari = $spd->jumlah_anggaran - $totalJanuari;
                                        echo 'Rp. ' . number_format($sisaAnggaranJanuari, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Januari --}}
                                {{-- Februari --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Februari')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Februari')
                                            ->sum('anggaran_digunakan');

                                        $totalFebruari = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalFebruari, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranFebruari = $sisaAnggaranJanuari - $totalFebruari;
                                        echo 'Rp. ' . number_format($sisaAnggaranFebruari, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Februari --}}
                                {{-- Maret --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Maret')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Maret')
                                            ->sum('anggaran_digunakan');

                                        $totalMaret = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalMaret, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranMaret = $sisaAnggaranFebruari - $totalMaret;
                                        echo 'Rp. ' . number_format($sisaAnggaranMaret, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Maret --}}
                                {{-- April --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'April')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'April')
                                            ->sum('anggaran_digunakan');

                                        $totalApril = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalApril, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranApril = $sisaAnggaranMaret - $totalApril;
                                        echo 'Rp. ' . number_format($sisaAnggaranApril, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End April --}}
                                {{-- Mei --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Mei')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Mei')
                                            ->sum('anggaran_digunakan');

                                        $totalMei = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalMei, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranMei = $sisaAnggaranApril - $totalMei;
                                        echo 'Rp. ' . number_format($sisaAnggaranMei, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Mei --}}
                                {{-- Juni --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Juni')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Juni')
                                            ->sum('anggaran_digunakan');

                                        $totalJuni = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalJuni, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranJuni = $sisaAnggaranMei - $totalJuni;
                                        echo 'Rp. ' . number_format($sisaAnggaranJuni, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Juni --}}
                                {{-- Juli --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Juli')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Juli')
                                            ->sum('anggaran_digunakan');

                                        $totalJuli = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalJuli, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranJuli = $sisaAnggaranJuni - $totalJuli;
                                        echo 'Rp. ' . number_format($sisaAnggaranJuli, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Juli --}}
                                {{-- Agustus --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Agustus')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Agustus')
                                            ->sum('anggaran_digunakan');

                                        $totalAgustus = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalAgustus, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranAgustus = $sisaAnggaranJuli - $totalAgustus;
                                        echo 'Rp. ' . number_format($sisaAnggaranAgustus, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Agustus --}}
                                {{-- September --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'September')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'September')
                                            ->sum('anggaran_digunakan');

                                        $totalSeptember = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalSeptember, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranSeptember = $sisaAnggaranAgustus - $totalSeptember;
                                        echo 'Rp. ' . number_format($sisaAnggaranSeptember, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End September --}}
                                {{-- Oktober --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Oktober')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Oktober')
                                            ->sum('anggaran_digunakan');

                                        $totalOktober = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalOktober, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranOktober = $sisaAnggaranSeptember - $totalOktober;
                                        echo 'Rp. ' . number_format($sisaAnggaranOktober, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Oktober --}}
                                {{-- November --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'November')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'November')
                                            ->sum('anggaran_digunakan');

                                        $totalNovember = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalNovember, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranNovember = $sisaAnggaranOktober - $totalNovember;
                                        echo 'Rp. ' . number_format($sisaAnggaranNovember, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End November --}}
                                {{-- Desember --}}
                                <td>
                                    @php
                                        $sppLs = \App\Models\SppLs::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Desember')
                                            ->sum('anggaran_digunakan');

                                        $sppGu = \App\Models\SppGu::where('sekretariat_daerah_id', $spd->sekretariat_daerah_id)
                                            ->orderBy('created_at', 'asc')
                                            ->where('tahun_id', $spd->tahun_id)
                                            ->where('kegiatan_dpa_id', $spd->kegiatan_dpa_id)
                                            ->where('status_validasi_akhir', 1)
                                            ->where('bulan', 'Desember')
                                            ->sum('anggaran_digunakan');

                                        $totalDesember = $sppLs + $sppGu;
                                        echo 'Rp. ' . number_format($totalDesember, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $sisaAnggaranDesember = $sisaAnggaranNovember - $totalDesember;
                                        echo 'Rp. ' . number_format($sisaAnggaranDesember, 0, ',', '.');
                                    @endphp
                                </td>
                                {{-- End Desember --}}
                                @if (Auth::user()->role == 'Admin')
                                    <td>
                                        <button id="btn-edit" class="btn btn-warning btn-sm mr-1"
                                            value="{{ $spd->id }}"><i class="fas fa-edit"></i> Ubah</button>
                                        <button id="btn-delete" class="btn btn-danger btn-sm mr-1"
                                            value="{{ $spd->id }}"> <i class="fas fa-trash-alt"></i>
                                            Hapus</button>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">Jumlah</td>
                            <td>Rp.
                                {{ number_format($totalJumlahAnggaran, 0, ',', '.') }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                    <td colspan="{{ Auth::user()->role == 'Admin' ? 29 : 28 }}" class="fw-bold text-center">Data
                        Tidak Ada</td>
                </tr>
            </tbody>
        @endif
    </table>
