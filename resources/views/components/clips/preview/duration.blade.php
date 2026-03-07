@props(['duration' => 0])
<x-clips.preview.container class="top-2 left-2">
    <x-lucide-clock class="size-3 sm:size-4 md:size-6 shrink-0" aria-hidden="true" defer />
    <p class="sr-only">Länge</p>
    <span class="font-mono">
        {{ round($duration) }}s
    </span>
</x-clips.preview.container>
