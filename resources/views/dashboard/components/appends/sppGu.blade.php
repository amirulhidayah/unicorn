 <div class="col-lg-12 my-3">
     <div class="border border-black rounded p-4">
         @component('dashboard.components.widgets.info', [
             'judul' => 'Nomor Surat Pertanggungjawaban (SPJ)',
             'isi' => $spjGu->nomor_surat,
         ])
         @endcomponent
         @component('dashboard.components.widgets.info', [
             'judul' => 'Sekretariat Daerah',
             'isi' => $spjGu->sekretariatDaerah->nama,
         ])
         @endcomponent
         @component('dashboard.components.widgets.info', [
             'judul' => 'Tahun',
             'isi' => $spjGu->tahun->tahun,
         ])
         @endcomponent
         @component('dashboard.components.widgets.info', [
             'judul' => 'Bulan',
             'isi' => $spjGu->bulan,
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
                         <th scope="col">Anggaran Digunakan</th>
                         <th scope="col">Sisa Anggaran</th>
                         <th scope="col">Dokumen</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($programDanKegiatan as $kegiatanSpjGu)
                         <tr>
                             <td>{{ $kegiatanSpjGu['program'] }}
                             </td>
                             <td>{{ $kegiatanSpjGu['kegiatan'] }}
                             </td>
                             <td>{{ number_format($kegiatanSpjGu['jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                             </td>
                             <td>{{ number_format($kegiatanSpjGu['anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                             </td>
                             <td>{{ number_format($kegiatanSpjGu['sisa_anggaran'] ?? 0, 0, ',', '.') }}
                             </td>
                             <td>
                                 <a target="_blank"
                                     href="{{ Storage::url('dokumen_spj_gu/' . $kegiatanSpjGu['dokumen']) }}"
                                     class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i>
                                     Lihat</a>
                             </td>
                         </tr>
                     @endforeach
                     <tr>
                         <td colspan="2" class="fw-bold text-center">Total</td>
                         <td>{{ number_format($totalProgramDanKegiatan['total_jumlah_anggaran'] ?? 0, 0, ',', '.') }}
                         </td>
                         <td>{{ number_format($totalProgramDanKegiatan['total_anggaran_digunakan'] ?? 0, 0, ',', '.') }}
                         </td>
                         <td>{{ number_format($totalProgramDanKegiatan['total_sisa_anggaran'] ?? 0, 0, ',', '.') }}
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>
     </div>
 </div>
