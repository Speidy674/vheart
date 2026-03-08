@props(['src' => null, 'alt' => null, 'viewBuffer' => 100, 'force' => false, 'fallback' => null, 'loading' => 'lazy'])

@if($force)
    <div {{ $attributes }}>
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            @if($fallback) style="--fallback: url('{{ $fallback }}');" @endif
            class="h-full w-full object-cover text-transparent relative after:content-[''] after:absolute after:inset-0 after:bg-(image:--fallback) after:bg-cover after:bg-center"
            loading="{{ $loading }}"
            decoding="async"
        />
    </div>
@else
    <div
        x-data="image('{{ $src }}', '{{ $alt }}')"
        x-intersect.margin.{{ $viewBuffer }}px.once="show()"
        {{ $attributes }}
    >
        @if(isset($placeholder))
            <div x-show="imageStatus === 'loading'" {{ $placeholder->attributes->twMerge('absolute inset-0 flex items-center justify-center') }}>
                {{ $placeholder }}
            </div>
        @endif

        @if(isset($error))
            <div x-show="imageStatus === 'error'" style="display: none;" {{ $error->attributes->twMerge('absolute inset-0 flex items-center justify-center') }}>
                {{ $error }}
            </div>
        @endif

        <template x-if="shown">
            <img
                x-bind="imageBindings"
                x-init="checkCached($el)"
                class="h-full w-full object-cover opacity-0 data-[status=loaded]:opacity-100 data-[cached=false]:transition-opacity data-[cached=false]:duration-300"
            />
        </template>

        <noscript>
            <img
                src="{{ $src }}"
                alt="{{ $alt }}"
                @if($fallback) style="--fallback: url('{{ $fallback }}');" @endif
                class="h-full w-full object-cover text-transparent relative after:content-[''] after:absolute after:inset-0 after:bg-(image:--fallback) after:bg-cover after:bg-center"
                loading="{{ $loading }}"
                decoding="async"
            />
        </noscript>
    </div>
@endif
