@use(Illuminate\Support\Number)
<a
    href="{{ $clip->getClipUrl() }}"
    aria-label="Clip öffnen: {{ $clip->title }}"
    target="_blank"
    {{ $attributes->twMerge('block group focus-visible:ring-primary-500 relative aspect-video w-full overflow-hidden rounded-md bg-gray-200 outline-none focus-visible:ring-2 dark:bg-gray-800') }}
>
    <x-image src="{{ $clip->proxiedContentUrl() }}" :fallback="Vite::asset('resources/images/webp/clips/no_thumbnail.webp')" class="aspect-video">
        <x-slot:placeholder class="animate-pulse">
            <x-lucide-video defer class="size-16 opacity-25" />
        </x-slot:placeholder>

        <x-slot:error>
            <x-lucide-video-off defer class="size-16 opacity-25" />
        </x-slot:error>
    </x-image>

    <x-clips.preview.duration :duration="round($clip->duration)" />
    <x-clips.preview.votes :votes="$clip->absolute_votes" />

    <x-clips.preview.container class="right-2 bottom-1 sm:bottom-2 left-2 block">
        <x-clips.preview.tags :clip="$clip" />

        <h3 class="line-clamp-1 text-xs font-medium sm:text-sm xl:text-base">
            {{ $clip->title }}
        </h3>

        <div class="truncate text-foreground font-bold text-xs sm:text-sm">
            {{ $clip->owner->name }}
        </div>
    </x-clips.preview.container>
</a>
