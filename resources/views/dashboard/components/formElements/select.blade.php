<label class="form-label my-2">{{ $label ?? '' }} {!! $wajib ?? '' !!}</label>
<select class="form-select {{ $class ?? '' }}" id="{{ $id ?? '' }}" aria-hidden="true" {{ $attribute ?? '' }}
    name="{{ $name ?? '' }}" style="width:100%">
    @if ($class == 'filter')
        <option value="">Semua</option>
    @else
        <option value="" selected hidden>- Pilih Salah Satu -</option>
    @endif
    {{ $options ?? '' }}
</select>
<span class="text-danger error-text {{ $name }}-error"></span>
