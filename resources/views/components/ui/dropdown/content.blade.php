@props(['align' => 'right', 'width' => 'w-56'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'left-0 origin-top-left',
        'top' => 'bottom-full mb-2 origin-bottom',
        default => 'right-0 origin-top-right',
    };
@endphp

<div
    x-show="open"
    style="display: none;"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute z-50 mt-2 {{ $width }} {{ $alignmentClasses }} rounded-xl border border-gray-200 bg-white/95 shadow-2xl backdrop-blur-lg focus:outline-none dark:border-white/10 dark:bg-black/90"
    role="menu"
    {{ $attributes }}
>
    <div class="p-2 text-sm text-gray-700 dark:text-gray-200">
        {{ $slot }}
    </div>
</div>
