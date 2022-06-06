<div class="table-responsive text-nowrap">
    <table class="table table-hover table-bordered" id="{{ $id }}" cellspacing="0" width="100%">
        <thead>
            <tr class="text-center fw-bold">
                @foreach ($th as $item)
                    <td>{{ $item }}</td>
                @endforeach
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
