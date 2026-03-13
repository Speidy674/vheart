@aware([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'radio',
    'name' => null,
])
@php
    static $toggleGroupBase = "relative select-none inline-flex items-center justify-center gap-2 text-sm font-medium transition-all duration-100 ease-in-out active:opacity-80 outline-none cursor-pointer [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 min-w-0 shrink-0 rounded-none shadow-none first:rounded-l-md last:rounded-r-md";
    static $toggleGroupHasCheckedState = "has-[:checked]:z-10";
    static $toggleGroupHasFocusState = "has-[:focus-visible]:ring-1 has-[:focus-visible]:ring-ring/50 has-[:focus-visible]:border-ring has-[:focus-visible]:z-10";
    static $toggleGroupHasInvalidState = "has-[:invalid]:ring-1 has-[:invalid]:ring-destructive/20 dark:has-[:invalid]:ring-destructive/40 has-[:invalid]:border-destructive";
    static $toggleGroupHasDisabledState = "has-[:disabled]:opacity-50 has-[:disabled]:cursor-not-allowed";

    static $variants = [
        'default' => 'bg-transparent hover:bg-muted hover:text-muted-foreground has-[:checked]:bg-accent/25 has-[:checked]:text-accent-foreground has-[:checked]:border-accent',
        'accent' => 'bg-transparent hover:bg-accent/10 has-[:checked]:bg-accent/50 has-[:checked]:text-accent-foreground has-[:checked]:border-accent',
        'primary' => 'bg-transparent hover:bg-primary/10 has-[:checked]:bg-primary has-[:checked]:text-primary-foreground has-[:checked]:border-primary',
        'secondary' => 'bg-transparent hover:bg-secondary/50 has-[:checked]:bg-secondary has-[:checked]:text-secondary-foreground has-[:checked]:border-secondary',
        'muted' => 'bg-transparent hover:bg-muted/50 has-[:checked]:bg-muted has-[:checked]:text-muted-foreground has-[:checked]:border-muted',
        'outline' => 'border border-input bg-transparent hover:bg-accent/20 hover:text-accent-foreground border-l-0 first:border-l has-[:checked]:bg-accent/25 has-[:checked]:text-accent-foreground has-[:checked]:border-accent',
        'ghost' => 'bg-transparent hover:bg-accent/10 hover:text-accent-foreground has-[:checked]:bg-accent/20 has-[:checked]:text-accent-foreground',
    ];

    static $sizes = [
        'default' => 'h-9 px-2 min-w-9',
        'sm' => 'h-8 px-1.5 min-w-8',
        'lg' => 'h-10 px-2.5 min-w-10',
    ];
@endphp
<label
    data-slot="toggle-group-item"
    {{ $attributes->only('class')->twMerge($toggleGroupBase, $toggleGroupHasCheckedState, $toggleGroupHasFocusState, $toggleGroupHasInvalidState, $toggleGroupHasDisabledState, $variants[$variant] ?? $variants['default'], $sizes[$size] ?? $sizes['default']) }}
>
    <input
        name="{{ $name }}"
        {{ $attributes->except(['class', 'name'])->merge(['class' => 'sr-only', 'type' => $type]) }}
    />
    {{ $slot }}
</label>
