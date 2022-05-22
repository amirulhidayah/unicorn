<div class="form-check form-check-inline">
    <label class="form-check-label" for="{{$id ?? ''}}">{{$label ?? ''}}</label>
    <input class="form-check-input" type="radio" id="{{$id ?? ''}}" name="{{$name ?? ''}}" data-parsley-multiple="radio" value="{{$value ?? ''}}">
</div>

