<label for="TextInput" class="form-label my-2">{{ $label }} {!! $wajib ?? '' !!}</label>
<input type="{{ $type ?? '' }}" id="{{ $id ?? '' }}" name="{{ $name ?? '' }}"
    class="form-control {{ $class ?? '' }}" value="{{ $value ?? '' }}" {{ $attribute ?? '' }}
    placeholder="{{ $placeholder ?? '' }}" autocomplete="off" accept="{{ $accept ?? '' }}">
<span class="text-danger error-text {{ $name }}-error"></span>
