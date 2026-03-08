@props(['src' => null, 'alt' => null, 'viewBuffer' => 100, 'force' => false])

@if($force)
    <div {{ $attributes }}>
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="h-full w-full object-cover"
            loading="lazy"
            decoding="async"
        />
    </div>
@else
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
                x-bind:data-status="imageStatus"
                x-bind:data-cached="isCached ? 'true' : 'false'"
                class="h-full w-full object-cover opacity-0 data-[status=loaded]:opacity-100 data-[cached=false]:transition-opacity data-[cached=false]:duration-300"
                loading="lazy"
                decoding="async"
            />
        </template>

        <noscript>
            <img
                src="{{ $src }}"
                alt="{{ $alt }}"
                class="h-full w-full object-cover"
                loading="lazy"
                decoding="async"
            />
        </noscript>
    </div>
@endif
