@props([
    'variant' => 'default',
    'size' => 'default',
])
@php
    static $toggleBase = "select-none inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition-[color,box-shadow] outline-none [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0";
    static $toggleFocused = "peer-focus-visible:ring-1 peer-focus-visible:ring-ring/50 peer-focus-visible:border-ring";
    static $toggleValidation = "peer-aria-invalid:ring-1 peer-aria-invalid:ring-destructive/20 dark:peer-aria-invalid:ring-destructive/40 peer-aria-invalid:border-destructive";
    static $toggleDisabled = "peer-disabled:opacity-50 peer-disabled:cursor-not-allowed";

    static $toggleVariants = [
        'default' => 'bg-transparent hover:bg-muted hover:text-muted-foreground peer-checked:bg-accent/25 peer-checked:text-accent-foreground peer-checked:border-accent',
        'outline' => 'border border-input bg-transparent shadow-xs hover:bg-accent/20 hover:text-accent-foreground peer-checked:bg-accent/25 peer-checked:text-accent-foreground peer-checked:border-accent',
        'ghost' => 'bg-transparent hover:bg-accent/10 hover:text-accent-foreground peer-checked:bg-accent/20 peer-checked:text-accent-foreground',
    ];

    static $toggleSizes = [
        'default' => 'h-9 px-2 min-w-9',
        'sm' => 'h-8 px-1.5 min-w-8',
        'lg' => 'h-10 px-2.5 min-w-10',
    ];
@endphp
<label data-slot="toggle" class="inline-flex cursor-pointer has-disabled:cursor-not-allowed">
    <input
        type="checkbox"
        {{ $attributes->merge(['class' => 'peer sr-only']) }}
    />
    <div
        {{ $attributes->only('class')->twMerge($toggleBase, $toggleFocused, $toggleValidation, $toggleDisabled, $toggleVariants[$variant] ?? $toggleVariants['default'], $toggleSizes[$size] ?? $toggleSizes['default']) }}
    >
        {{ $slot }}
    </div>
</label>
