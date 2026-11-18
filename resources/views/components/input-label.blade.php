@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-slate-900']) }}>
    {{ $value ?? $slot }}
</label>
