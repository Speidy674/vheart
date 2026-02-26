{{-- TODO --}}
<x-ui.dropdown>
    <x-ui.dropdown.trigger>
        <x-ui.button variant="ghost">
            <x-lucide-globe defer class="size-4" />
            <span class="uppercase">{{ app()->currentLocale() }}</span>
            <x-lucide-chevron-up defer class="size-4 transition-transform" x-bind:class="{ 'rotate-180': open }" />
        </x-ui.button>
    </x-ui.dropdown.trigger>
    <x-ui.dropdown.content>
        @foreach(config('app.locales') as $locale => $config)
            <x-ui.dropdown.item class="justify-between" href="{{ route('locales', ['locale' => $locale]) }}">
                <span>{{ $config['name'] }}</span>
                @if($locale === app()->getLocale())
                    <x-lucide-check defer class="size-4" />
                @endif
            </x-ui.dropdown.item>
        @endforeach
    </x-ui.dropdown.content>
</x-ui.dropdown>
