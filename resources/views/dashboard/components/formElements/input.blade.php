@if (isset($label))
    <label for="TextInput" class="form-label my-2 fw-bold">{{ $label ?? '' }} {!! $wajib ?? '' !!}</label>
@endif
<input type="{{ $type ?? '' }}" id="{{ $id ?? '' }}" name="{{ $name ?? '' }}"
    class="form-control {{ $class ?? '' }}" value="{{ $value ?? '' }}" {{ $attribute ?? '' }}
    placeholder="{{ $placeholder ?? '' }}" autocomplete="off" accept="{{ $accept ?? '' }}" data-key="{{ $dataKey ?? '' }}">
<span class="text-danger error-text {{ $name }}-error"></span>
