@props(['status' => 'active'])

@php
    $isSuspended = $status === \App\Models\Tenant::STATUS_SUSPENDED;
@endphp

<span
    {{ $attributes->merge([
        'class' => 'admin-status-badge '.($isSuspended ? 'is-suspended' : ''),
    ]) }}
>
    <span class="admin-status-dot" aria-hidden="true"></span>
    {{ $isSuspended ? __('Suspended') : __('Active') }}
</span>
