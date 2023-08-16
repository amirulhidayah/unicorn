@if (count($array) > 0)
    <div id="tabel-spd">
        <table class="table table-bordered table-hover text-nowrap col-12">
            <thead class="text-center">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">NAMA SUB OPD, PROGRAM DAN KEGIATAN</th>
                    <th scope="col">NO. REK. KEG. SKPD</th>
                    <th scope="col">Jumlah Anggaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($array['data'] as $data)
                    <tr>
                        <td colspan="7" class="fw-bold">
                            {{ $data['sekretariat_daerah'] }}</td>
                    </tr>
                    @foreach ($data['program'] as $program)
                        <tr>
                            <td colspan="2">{{ $program['nama'] }}</td>
                            <td colspan="5">
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
                                <td class="text-center">
                                    <button id="btn-edit" class="btn btn-warning btn-sm mr-1"
                                        value="{{ $kegiatan['id'] }}"><i class="fas fa-edit"></i> Ubah</button>
                                    <button id="btn-delete" class="btn btn-danger btn-sm mr-1"
                                        value="{{ $kegiatan['id'] }}"> <i class="fas fa-trash-alt"></i>
                                        Hapus</button>
                                </td>
                            </tr>
                        @endforeach
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
