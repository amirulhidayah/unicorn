@if (count($array) > 0)
    <table class="table table-bordered table-responsive table-hover text-nowrap">
        <thead class="text-center">
            <tr>
                <th scope="col" rowspan="2">No.</th>
                <th scope="col" rowspan="2">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                <th scope="col" rowspan="2">NO. REK. KEG. SKPD</th>
                @foreach ($array['bulan'] as $bulan)
                    <th scope="col" colspan="3">{{ $bulan }}</th>
                @endforeach
                <th scope="col" colspan="3">Total</th>
            </tr>
            <tr>
                @foreach ($array['bulan'] as $bulan)
                    <td>
                        Perencanaan Anggaran
                    </td>
                    <td>
                        Anggaran Digunakan
                    </td>
                    <td>
                        Sisa Anggaran
                    </td>
                @endforeach
                <td>
                    Perencanaan Anggaran
                </td>
                <td>
                    Anggaran Digunakan
                </td>
                <td>
                    Sisa Anggaran
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($array['data'] as $data)
                <tr>
                    <td colspan="{{ 6 + 3 * count($array['bulan']) }}" class="fw-bold">
                        {{ $data['sekretariat_daerah'] }}</td>
                </tr>
                @foreach ($data['program'] as $program)
                    <tr>
                        <td colspan="2">{{ $program['nama'] }}</td>
                        <td colspan="{{ 4 + 3 * count($array['bulan']) }}">
                            {{ $program['no_rek'] }}</td>
                    </tr>
                    @foreach ($program['kegiatan'] as $kegiatan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kegiatan['nama'] }}</td>
                            <td>{{ $kegiatan['no_rek'] }}</td>
                            @foreach ($kegiatan['bulan'] as $bulan)
                                <td>
                                    {{ 'Rp. ' . number_format($bulan['perencanaan_anggaran'], 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ 'Rp. ' . number_format($bulan['anggaran_digunakan'], 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ 'Rp. ' . number_format($bulan['sisa_anggaran'], 0, ',', '.') }}
                                </td>
                            @endforeach
                            <td>
                                {{ 'Rp. ' . number_format($kegiatan['total_perencanaan_anggaran'], 0, ',', '.') }}
                            </td>
                            <td>
                                {{ 'Rp. ' . number_format($kegiatan['total_anggaran_digunakan'], 0, ',', '.') }}
                            </td>
                            <td>
                                {{ 'Rp. ' . number_format($kegiatan['total_sisa_anggaran'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center">
                            Jumlah
                        </td>
                        @foreach ($program['total_bulan']['bulan'] as $total)
                            <td> {{ 'Rp. ' . number_format($total['perencanaan_anggaran'], 0, ',', '.') }}
                            </td>
                            <td> {{ 'Rp. ' . number_format($total['anggaran_digunakan'], 0, ',', '.') }}
                            </td>
                            <td> {{ 'Rp. ' . number_format($total['sisa_anggaran'], 0, ',', '.') }}
                            </td>
                        @endforeach
                        <td> {{ 'Rp. ' . number_format($program['total_bulan']['perencanaan_anggaran'], 0, ',', '.') }}
                        </td>
                        <td> {{ 'Rp. ' . number_format($program['total_bulan']['anggaran_digunakan'], 0, ',', '.') }}
                        </td>
                        <td> {{ 'Rp. ' . number_format($program['total_bulan']['sisa_anggaran'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center mt-5">
        <span class="badge badge-primary">Data Tidak Ada</span>
    </div>
@endif
