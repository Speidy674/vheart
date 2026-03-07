@use(Illuminate\Support\Number)
@props(['votes' => 0])
<x-clips.preview.container
    title="{{ Number::format($votes) }}"
    class="top-2 right-2"
>
    <x-lucide-heart class="text-red-500 size-3 sm:size-4 md:size-6" aria-hidden="true" defer />
    <p class="sr-only">Stimmen</p>
    <span class=" ng-none">{{ Number::abbreviate($votes, maxPrecision: 1) }}</span>
</x-clips.preview.container>
