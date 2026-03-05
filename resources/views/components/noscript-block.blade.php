@props(['url' => null, 'label' => null])
{{-- Even though we should try to make it work without javascript, some elements or features just cant work without --}}
{{-- this blocks the user from the entire feature visually and notifies them about the issue --}}
<noscript>
    <div {{ $attributes->twMerge("absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-black/50 backdrop-blur-sm p-4") }}>
        <x-ui.card class="max-w-lg w-full">
            <x-ui.card.header>
                <x-ui.card.title>
                    <h2 class="text-2xl font-bold text-center w-full">{{ __('messages.noscript.overlay.heading') }}</h2>
                </x-ui.card.title>
            </x-ui.card.header>
            <x-ui.card.content class="text-center text-balance space-y-2">
                <p>{{ __('messages.noscript.overlay.paragraph-1') }}</p>
                <p>{{ __('messages.noscript.overlay.paragraph-2') }}</p>

                @if($url)
                    <x-ui.button as="a" href="{{ $url }}" variant="link" class="w-full">
                        {{ $label ?? __('messages.noscript.overlay.reload') }}
                    </x-ui.button>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</noscript>
