@props([
    'side' => 'top',
    'offset' => 4,
])
<template x-teleport="body">
    <div
        x-show="open"
        style="display: none;"
        x-anchor.{{ $side }}.offset.{{ $offset }}="$refs.trigger"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        data-slot="tooltip-content"
        data-side="{{ $side }}"
        {{ $attributes->twMerge('z-90 w-max max-w-sm rounded-md bg-secondary px-3 py-1.5 text-xs text-secondary-foreground shadow-md') }}
    >
        {{ $slot }}
    </div>
</template>
