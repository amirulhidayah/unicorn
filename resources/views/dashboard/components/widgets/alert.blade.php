<style>
    #custom-alert .card-header {
        border: 1px solid grey;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
    }

    #custom-alert-judul {
        font-size: 18px;
    }

</style>

<div class="card {{ $classBg }}" id="custom-alert">
    <div class="card-header bg-light text-dark">
        <span class="fw-bold" id="custom-alert-judul">{{ $judul ?? '' }}</span>
    </div>
    <div class="card-body">
        {{ $isi }}
    </div>
</div>
