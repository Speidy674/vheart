@props([
    'variant' => 'default',
])
@php
    static $radioBase = "peer appearance-none grid place-content-center border-input size-4 shrink-0 rounded-full border shadow-xs transition-shadow outline-none";
    static $radioFocused = "focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-2";
    static $radioValidation = "aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";
    static $radioDisabled = "disabled:cursor-not-allowed disabled:opacity-50";

    static $radioVariants = [
        'default' => 'hover:border-primary/50 checked:bg-primary checked:border-primary',
    ];
@endphp
<div class="relative inline-flex size-4 items-center justify-center align-middle">
    <input
        type="radio"
        data-slot="radio"
        {{ $attributes->twMerge($radioBase, $radioFocused, $radioValidation, $radioDisabled, $radioVariants[$variant] ?? $radioVariants['default']) }}
    />
    <div
        data-slot="radio-indicator"
        class="pointer-events-none absolute inset-0 flex items-center align-middle justify-center opacity-0 peer-checked:opacity-100 transition-none"
    >
        <div class="size-2.5 self-center rounded-full bg-primary-foreground"></div>
    </div>
</div>
