@php
    static $textareaBase = "flex min-h-20 w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground md:text-sm";
    static $textareaFocused = "focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-1";
    static $textareaValidation = "aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";
    static $textareaDisabled = "disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50";
@endphp
<textarea {{ $attributes->twMerge($textareaBase, $textareaFocused, $textareaValidation, $textareaDisabled) }}>{{ $slot }}</textarea>
