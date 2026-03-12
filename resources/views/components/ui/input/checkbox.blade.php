@props([
    'variant' => 'default',
])
@php
    static $checkboxBase = "peer appearance-none grid place-content-center border-input size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none";
    static $checkboxFocused = "focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-2";
    static $checkboxValidation = "aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";
    static $checkboxDisabled = "disabled:cursor-not-allowed disabled:opacity-50";

    static $checkboxVariants = [
        'default' => 'hover:border-primary/50 checked:bg-primary checked:text-primary-foreground checked:border-primary',
    ];
@endphp
<div class="relative inline-flex items-center justify-center align-middle">
    <input
        type="checkbox"
        data-slot="checkbox"
        {{ $attributes->twMerge($checkboxBase, $checkboxFocused, $checkboxValidation, $checkboxDisabled, $checkboxVariants[$variant] ?? $checkboxVariants['default']) }}
    />
    <div
        data-slot="checkbox-indicator"
        class="pointer-events-none absolute inset-0 flex items-center justify-center text-primary-foreground opacity-0 peer-checked:opacity-100 transition-none"
    >
        <x-lucide-check defer class="size-3.5"/>
    </div>
</div>
