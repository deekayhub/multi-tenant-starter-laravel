@props(['value'])

<label {{ $attributes->merge(['class' => 'field-label leading-5']) }}>
    {{ $value ?? $slot }}
</label>
