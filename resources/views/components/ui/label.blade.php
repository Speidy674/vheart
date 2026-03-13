@php
    static $labelBase = "text-sm leading-none font-medium select-none";
    static $labelDisabled = "group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50";
    static $labelRequired = "data-[required=true]:after:content-['*'] data-[required=true]:after:text-destructive";
@endphp
<label
    data-slot="label"
    @if($attributes->get('required')) data-required="true" @endif
    {{ $attributes->except('required')->twMerge($labelBase, $labelDisabled, $labelRequired) }}
>
    {{ $slot }}
</label>
