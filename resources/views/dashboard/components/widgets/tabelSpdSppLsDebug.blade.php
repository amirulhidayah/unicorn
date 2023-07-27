@if (count($array) > 0)
    <div id="tabel-spd">
        <table class="table table-bordered table-responsive table-hover text-nowrap">
            <thead class="text-center">
                <tr>
                    <th scope="col" rowspan="2">No.</th>
                    <th scope="col" rowspan="2">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                    <th scope="col" rowspan="2">NO. REK. KEG. SKPD</th>
                    <th scope="col" rowspan="2">Jumlah Anggaran</th>
                    @foreach ($array['bulan'] as $bulan)
                        <th scope="col" colspan="2">{{ $bulan }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($array['bulan'] as $bulan)
                        <td>
                            Anggaran Digunakan
                        </td>
                        <td>
                            Sisa Anggaran
                        </td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($array['data'] as $data)
                    <tr>
                        <td colspan="{{ 4 + 2 * count($array['bulan']) }}" class="fw-bold">
                            {{ $data['sekretariat_daerah'] }}</td>
                    </tr>
                    @foreach ($data['program'] as $program)
                        <tr>
                            <td colspan="2">{{ $program['nama'] }}</td>
                            <td colspan="{{ 2 + 2 * count($array['bulan']) }}">
                                {{ $program['no_rek'] }}</td>
                        </tr>
                        @foreach ($program['kegiatan'] as $kegiatan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kegiatan['nama'] }}</td>
                                <td>{{ $kegiatan['no_rek'] }}</td>
                                <td>
                                    {{ 'Rp. ' . number_format($kegiatan['jumlah_anggaran'], 0, ',', '.') }}
                                </td>
                                @foreach ($kegiatan['bulan'] as $bulan)
                                    <td>
                                        {{ 'Rp. ' . number_format($bulan['anggaran_digunakan'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ 'Rp. ' . number_format($bulan['sisa_anggaran'], 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">
                                Jumlah
                            </td>
                            <td> {{ 'Rp. ' . number_format($program['jumlah_anggaran'], 0, ',', '.') }}
                            </td>
                            @foreach ($program['total_bulan']['bulan'] as $total)
                                <td> {{ 'Rp. ' . number_format($total['anggaran_digunakan'], 0, ',', '.') }}
                                </td>
                                <td> {{ 'Rp. ' . number_format($total['sisa_anggaran'], 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center mt-5">
        <span class="badge badge-primary">Data Tidak Ada</span>
    </div>
@endif
