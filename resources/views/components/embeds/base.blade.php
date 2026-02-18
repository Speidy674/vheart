<div
    {{ $attributes->merge(['class' => "relative isolate overflow-hidden aspect-video rounded-lg bg-black dark:border-black"]) }}
>
    {{-- later we can add some fallback stuff so this can technically work with no javascript in a very basic way but for now this is enough --}}
    <noscript>
        <div class="absolute inset-0 z-20 h-full w-full bg-black">
            <div class="flex h-full flex-row items-center justify-center gap-4 p-6 text-center text-white">
                {{-- TODO: use icon stuff later --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-12 text-destructive"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <p>{{ __('embeds.generic.noscript.text') }}</p>
            </div>
        </div>
    </noscript>

    <template x-if="!isValidUrl">
        <div class="absolute inset-0 z-20 h-full w-full bg-black">
            <div class="flex h-full flex-row items-center justify-center gap-4 p-6 text-center text-white">
                {{-- TODO: use icon stuff later --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-12 text-destructive"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <p>{{ __('embeds.generic.invalid.text') }}</p>
            </div>
        </div>
    </template>

    <template x-if="isValidUrl && url">
        <div class="h-full w-full">
            <template x-if="!hasConsent()">
                <div class="absolute inset-0 z-20 h-full w-full bg-black">
                    <div class="flex h-full flex-col items-center justify-center space-y-4 p-6 text-center text-white">
                        <p class="text-base font-medium text-balance text-zinc-400">
                            {{ __('embeds.generic.consent.text') }}
                        </p>

                        <button
                            @click="accept()"
                            class="text-md rounded bg-zinc-600 px-4 py-2 font-bold text-white transition hover:bg-zinc-500 hover:text-white"
                        >
                            {{ __('embeds.generic.consent.button') }}
                        </button>

                        <template x-if="link" >
                            <a
                                :href="link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-md text-zinc-500 underline hover:text-zinc-300"
                            >
                                {{ __('embeds.generic.consent.link-text') }}
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="hasConsent()">
                <div class="h-full w-full relative">

                    <div x-show="isLoading" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black text-gray-500">
                        {{-- TODO: use icon stuff later --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-12 animate-spin opacity-75"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                    </div>

                    <iframe
                        :src="url"
                        :title="title"
                        @load="handleIframeLoad()"
                        class="h-full w-full border-0 transition-opacity duration-500"
                        :class="isLoading ? 'opacity-0' : 'opacity-100'"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                        allowFullScreen
                        loading="lazy"
                    ></iframe>
                </div>
            </template>
        </div>
    </template>
</div>
