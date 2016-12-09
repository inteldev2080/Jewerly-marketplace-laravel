@foreach ($attributes as $attribute)
<option disabled> ---- Values for {{$attribute->name}} ---- </option>
    @foreach ($attribute->values as $value)
        <option value="{{ $value->id }}" @if(isset($values_selected) && in_array($value->id, $values_selected)) selected  @endif data-tokens="{{ $value->name }}">
            {{$attribute->name}} : {{ $value->name }}</option>
    @endforeach
@endforeach
