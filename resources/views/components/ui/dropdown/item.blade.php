@props(['href' => '#', 'destructive' => false, 'as' => 'a'])

@php
    $baseClasses = 'flex w-full select-none items-center rounded-sm px-2 py-1.5 outline-none transition-colors text-left cursor-pointer';
    $colorClasses = $destructive
        ? 'font-medium text-red-600 hover:bg-red-100 focus:bg-red-100 dark:text-red-500 dark:hover:bg-red-900/50 dark:focus:bg-red-900/50'
        : 'hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white dark:focus:bg-gray-800';
@endphp

@if($as === 'button')
    <button @click="open = false" {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }} role="menuitem">
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" @click="open = false" {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }} role="menuitem">
        {{ $slot }}
    </a>
@endif
