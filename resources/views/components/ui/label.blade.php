@php
    static $labelBase = "text-sm leading-none font-medium select-none";
    static $labelDisabled = "group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50";
    static $labelRequired = "data-[required=true]:after:content-['*'] data-[required=true]:after:text-destructive";
@endphp
<label
    data-slot="label"
    data-required="{{ $attributes->get('required') ? 'true' : 'false' }}"
    {{ $attributes->except('required')->twMerge($labelBase, $labelDisabled, $labelRequired) }}
>
    {{ $slot }}
</label>
