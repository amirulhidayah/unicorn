@push('style')
    <style>
        .timeline-panel {
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.25) !important;
        }

        .btn {
            z-index: 999999 !important;
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

                    if ($riwayat->user->role == 'Bendahara Pembantu') {
                        $timelineMode = '';
                    } else {
                        $timelineMode = 'timeline-inverted';
                    }

                    if ($riwayat->status == 'Dibuat') {
                        $timelineTitle = 'Upload Dokumen';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' mengupload dokumen';
                        $timelineBadgeBgColor = 'bg-primary';
                        $timelineBadgeIcon = 'fas fa-file-upload';
                    } elseif ($riwayat->status == 'Disetujui') {
                        $timelineTitle = 'Disetujui';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' menyetujui dokumen';
                        $timelineBadgeBgColor = 'bg-success';
                        $timelineBadgeIcon = 'fas fa-check';
                    } elseif ($riwayat->status == 'Ditolak') {
                        $timelineTitle = 'Ditolak';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' menolak dokumen';
                        $timelineBadgeBgColor = 'bg-danger';
                        $timelineBadgeIcon = 'fas fa-times';
                        $surat = '<a href="' . url('/surat-penolakan' . '/' . $tipeSuratPenolakan . '/' . $riwayat->id) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Penolakan</a>';
                    } elseif ($riwayat->status == 'Diperbaiki') {
                        $timelineTitle = 'Diperbaiki';
                        $timelineBody = $riwayat->profil->nama . ' (' . $riwayat->user->role . ') ' . ' memperbaiki dokumen';
                        $timelineBadgeBgColor = 'bg-warning';
                        $timelineBadgeIcon = 'fas fa-edit';
                        $surat = '<a href="' . Storage::url('surat_penolakan_' . $tipeSuratPengembalian . '/' . $riwayat->surat_penolakan) . '" class="btn btn-primary btn-sm mt-1"><i class="fas fa-file-pdf"></i> Surat Pengembalian</a>';
                    }

                @endphp
                <li class="{{ $timelineMode }}">
                    <div class="timeline-badge {{ $timelineBadgeBgColor }}"><i class="{{ $timelineBadgeIcon }}"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $timelineTitle }}</h4>
                            <p class="my-2"><small class="text-muted"><i class="far fa-calendar"></i>
                                    {{ Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d F Y') }}
                                    oleh {{ $riwayat->profil->nama }}</small></p>
                        </div>
                        <div class="timeline-body">
                            <p>{{ $timelineBody }}</p>
                            {!! $surat !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
