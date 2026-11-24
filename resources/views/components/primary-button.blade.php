<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary whitespace-nowrap']) }}>
    {{ $slot }}
</button>
