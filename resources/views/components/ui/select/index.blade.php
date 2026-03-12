@php
    static $selectBase = "appearance-none block h-9 w-full rounded-md border border-input bg-background text-foreground pl-3 pr-8 py-1.5 text-sm shadow-xs transition-[color,box-shadow] outline-none";
    static $selectFocused = "focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50";
    static $selectValidation = "aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40";
    static $selectDisabled = "disabled:cursor-not-allowed disabled:opacity-50";
@endphp
<div class="relative h-fit">
    <select
        data-slot="select"
        {{ $attributes->twMerge($selectBase, $selectFocused, $selectValidation, $selectDisabled) }}
    >
        {{ $slot }}
    </select>

    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-input-foreground">
        <x-lucide-chevron-down defer class="size-4 opacity-50" />
    </div>
</div>
