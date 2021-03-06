     @push('style')
         <style>
             .media {
                 box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.25) !important;
                 border: 1px solid grey;
                 border-radius: 10px;
                 padding: 15px;
             }
         </style>
     @endpush


     <ul class="list-unstyled">
         @if (in_array(Auth::user()->role, ['PPK', 'ASN Sub Bagian Keuangan']) && $spp->surat_penolakan != null && ($spp->status_validasi_ppk != 1 || $spp->status_validasi_asn != 1))
             <li class="media mb-3 d-flex align-items-center" style="background-color: yellow">
                 <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="35px">
                 <div class="media-body">
                     <h5 class="font-16 mb-1 ml-2 my-0 fw-bold">Surat Penolakan<i
                             class="feather icon-download-cloud float-right"></i></h5>
                 </div>
                 <a href="{{ Storage::url('surat_penolakan_' . $tipe . '/' . $spp->surat_penolakan) }}"
                     class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i>
                     Lihat</a>
             </li>
             <hr>
         @endif
         @foreach ($dokumenSpp as $dokumen)
             <li class="media mb-3 d-flex align-items-center">
                 <img src="{{ asset('assets/dashboard/img/pdf.png') }}" alt="" width="35px">
                 <div class="media-body">
                     <h5 class="font-16 mb-1 ml-2 my-0 mr-1 fw-bold">{{ $dokumen->nama_dokumen }}<i
                             class="feather icon-download-cloud float-right"></i></h5>
                 </div>
                 <a href="{{ Storage::url('dokumen_' . $tipe . '/' . $dokumen->dokumen) }}"
                     class="btn btn-primary btn-sm"><i class="fas fa-file-download"></i>
                     Lihat</a>
             </li>
         @endforeach
     </ul>
