@props(['src' => null, 'alt' => null, 'viewBuffer' => 100])

<div
    x-data="image({ viewBuffer: {{ $viewBuffer }} })"
    x-intersect.margin.{{ $viewBuffer }}px.once="show()"
    {{ $attributes }}
>
    <template x-if="shown">
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            x-init="checkCached($el)"
            @load="imageStatus = 'loaded'"
        @@error="imageStatus = 'error'"
        :class="{
                'opacity-100': imageStatus === 'loaded',
                'opacity-0': imageStatus !== 'loaded',
                'transition-opacity duration-300': !isCached
            }"
        class="h-full w-full object-cover"
        loading="lazy"
        decoding="async"
        />
    </template>

    <noscript>
        {{-- should be replaced with an placeholder image later lol --}}
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="h-full w-full object-cover"
            loading="lazy"
            decoding="async"
        />
    </noscript>
</div>
