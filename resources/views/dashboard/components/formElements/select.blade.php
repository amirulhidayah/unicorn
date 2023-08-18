<div class="d-inline">
    @if (isset($label))
        <label class="form-label my-2 fw-bold">{{ $label ?? '' }} {!! $wajib ?? '' !!}
    @endif
    </label>
    @if ($btnId ?? '')
        <button class="btn btn-primary btn-sm mt-1 float-right" id="{{ $btnId ?? '' }}" class="{{ $btnClass ?? '' }}"
            type="button"><i class="fas fa-plus-circle"></i> Tambah</button>
    @endif
</div>

<div class="d-flex">
    <select class="form-select {{ $class ?? '' }} col-12" id="{{ $id ?? '' }}" aria-hidden="true"
        {{ $attribute ?? '' }} name="{{ $name ?? '' }}" autocomplete="off" data-key="{{ $dataKey ?? '' }}">
        @if ($class == 'filter')
            <option value="">Semua</option>
        @else
            <option value="" selected hidden>- Pilih Salah Satu -</option>
        @endif
        {{ $options ?? '' }}
    </select>
</div>

<span class="text-danger error-text {{ $name }}-error"></span>
