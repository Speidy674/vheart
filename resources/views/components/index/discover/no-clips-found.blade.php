<div class="flex flex-col items-center justify-center">
    <x-lucide-video-off defer class="size-12 text-muted-foreground mb-4" />
    <h2 class="text-lg font-semibold">
        {{ __('index.clips.no-clips') }}
    </h2>
    <p class="mt-2 text-sm text-muted-foreground text-center max-w-md text-balance">
        {{ __('index.clips.no-clips-subtext') }}
    </p>
    <div class="mt-6">
        <x-ui.button variant="outline" href="{{ route('submitclip.create') }}">
            <x-lucide-send defer />

            {{ __('index.clips.submit') }}
        </x-ui.button>
    </div>
</div>
