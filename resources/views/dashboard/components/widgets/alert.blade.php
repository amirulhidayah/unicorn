<div class="card card-post card-round">
    <div class="card-body">
        <div class="d-flex">
            <div class="avatar">
                <img src="{{ asset('assets/dashboard') }}/img/notes.png" alt="..." class="avatar-img rounded-circle">
            </div>
            <div class="info-post ml-2">
                <p class="username">Catatan {{ $oleh == 'ppk' ? 'PPK' : 'ASN Sub Bagian Keuangan' }} :</p>
                <p class="date text-muted">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        <div class="separator-solid"></div>
        <p class="card-text">{{ $isi }}</p>
    </div>
</div>
