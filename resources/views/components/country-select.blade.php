<select class="country-select" data-trigger name="country">
    <option value="">Select</option>
    @foreach ($countries as $item)
        <option value="{{ $item->iso2 }}" @selected(!empty($value && $value === $item->iso2))>{{ $item->name }}</option>
    @endforeach
</select>
