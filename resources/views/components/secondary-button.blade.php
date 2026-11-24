<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary whitespace-nowrap']) }}>
    {{ $slot }}
</button>
