@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'radio',
    'name' => null,
])
<div
    data-slot="toggle-group"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    {{ $attributes->twMerge('group/toggle-group flex items-center rounded-md data-[variant=outline]:shadow-xs') }}
>
    {{ $slot }}
</div>
