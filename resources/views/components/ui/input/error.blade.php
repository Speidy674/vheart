@if ($slot->isNotEmpty())
    <p {{ $attributes->twMerge('text-sm font-medium text-destructive') }}>
        {{ $slot }}
    </p>
@endif
