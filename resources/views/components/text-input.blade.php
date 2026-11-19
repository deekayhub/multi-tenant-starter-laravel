@props(['disabled' => false, 'errorBag' => null])

@php
    $fieldName = $attributes->get('name');
    $validationBag = $errorBag ?? ($errors ?? null);
    $fieldHasError = is_string($fieldName) && $validationBag && $validationBag->get($fieldName);
@endphp

<input
    @disabled($disabled)
    @if ($fieldHasError) aria-invalid="true" aria-describedby="{{ $fieldName }}-error" @endif
    {{ $attributes->merge(['class' => 'field-input'.($fieldHasError ? ' field-input-error' : '')]) }}
>
