@push('style')
    <style>
        .timeline-panel {
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.25) !important;
            /* border: 1px solid grey !important; */
        }

        .btn {
            z-index: 999999 !important;
        }

        .timeline>li:not(.timeline-inverted)+li.timeline-inverted {
            margin-top: 0px !important;
        }

        .timeline>li.timeline-inverted+li:not(.timeline-inverted) {
            margin-top: 0px !important;
        }

        .timeline>li>.timeline-panel::after {
            position: absolute;
            top: 27px;
            right: -14px;
            display: inline-block;
            border-top: 0px solid transparent !important;
            border-left: 0px solid transparent !important;
            border-right: 0 solid transparent !important;
            border-bottom: 0px solid transparent !important;
            content: "";
        }

        .timeline>li>.timeline-badge {
            color: #ffffff;
            width: 35px;
            height: 35px;
            line-height: 28px;
            font-size: 1.8em;
            text-align: center;
            position: absolute;
            top: 16px;
            left: 50.5%;
            margin-left: -25px;
            background-color: #999999;
            z-index: 100;
            border-top-right-radius: 50%;
            border-top-left-radius: 50%;
            border-bottom-right-radius: 50%;
            border-bottom-left-radius: 50%;
        }

        .fa-2xs {
            font-size: 15px !important;
        }
    </style>
@endpush


<div class="row">
    <div class="col-md-12">

        <ul class="timeline">
            @foreach ($daftarRiwayat as $riwayat)
                @php
                    $timelineTitle = '';
                    $timelineBody = '';
                    $timelineBadgeBgColor = '';
                    $timelineBadgeIcon = '';
                    $timelineMode = ''; //timeline-badge dan timeline-inverted
                    $surat = '';
                    $catatan = '';

                    if (in_array($riwayat->user->role, ['Admin', 'Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])) {
                        $timelineMode = '';
                    } else {
                        $timelineMode = 'timeline-inverted';
                    }

                    if ($riwayat->status == 'Dibuat') {
                        $timelineTitle = 'Upload Dokumen';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' mengupload dokumen';
                        $timelineBadgeBgColor = 'bg-primary';
                        $timelineBadgeIcon = 'fas fa-file-upload';
                    } elseif ($riwayat->status == 'Dibuat Tahap Awal') {
                        $timelineTitle = 'Upload Dokumen Tahap Awal';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' mengupload dokumen tahap awal';
                        $timelineBadgeBgColor = 'bg-primary';
                        $timelineBadgeIcon = 'fas fa-file-upload';
                    } elseif ($riwayat->status == 'Dibuat Tahap Akhir') {
                        $timelineTitle = 'Upload Dokumen Tahap Akhir';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' mengupload dokumen tahap akhir';
                        $timelineBadgeBgColor = 'bg-primary';
                        $timelineBadgeIcon = 'fas fa-file-upload';
                    } elseif ($riwayat->status == 'Disetujui') {
                        $timelineTitle = 'Disetujui';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' menyetujui dokumen';
                        $timelineBadgeBgColor = 'bg-success';
                        $timelineBadgeIcon = 'fas fa-check';
                    } elseif ($riwayat->status == 'Diselesaikan') {
                        $timelineTitle = 'Diselesaikan';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' menyelesaikan dokumen';
                        $timelineBadgeBgColor = 'bg-success';
                        $timelineBadgeIcon = 'fas fa-check';
                        $surat = '<a href="' . url('/surat-pernyataan' . '/' . $tipeSuratPenolakan . '/' . $spp->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i>  Surat Pernyataan</a>';
                    } elseif ($riwayat->status == 'Ditolak') {
                        $timelineTitle = 'Ditolak';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' menolak dokumen';
                        $timelineBadgeBgColor = 'bg-danger';
                        $timelineBadgeIcon = 'fas fa-times';
                        if ($riwayat->tahap_riwayat != $spp->tahap_riwayat || ($spp->status_validasi_asn != null && $spp->status_validasi_ppk != null)) {
                            $surat = '<a href="' . url('/surat-penolakan' . '/' . $tipeSuratPenolakan . '/' . $spp->id . '/' . $riwayat->tahap_riwayat) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Penolakan</a>';
                        }
                        $catatan = $riwayat->alasan;
                    } elseif ($riwayat->status == 'Diperbaiki') {
                        $timelineTitle = 'Diperbaiki';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' memperbaiki dokumen';
                        $timelineBadgeBgColor = 'bg-warning';
                        $timelineBadgeIcon = 'fas fa-pencil-alt';
                        $surat = '<a href="' . Storage::url('surat_penolakan_' . $tipeSuratPengembalian . '/' . $riwayat->surat_penolakan) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';
                    }

                @endphp
                <li class="{{ $timelineMode }}">
                    <div class="timeline-badge {{ $timelineBadgeBgColor }}"><i
                            class="{{ $timelineBadgeIcon }} fa-2xs"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $timelineTitle }}</h4>
                            <p class="my-2"><small class="text-muted"><i class="far fa-calendar"></i>
                                    {{ Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d F Y') }}
                                    oleh {{ $riwayat->profil->nama }}</small></p>
                        </div>
                        <div class="timeline-body">
                            <p class="my-3">{{ $timelineBody }}</p>
                            @if ($catatan)
                                <hr>
                                <p class="mt-0 mb-3">Catatan : {!! $catatan !!}</p>
                            @endif
                            {!! $surat !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
