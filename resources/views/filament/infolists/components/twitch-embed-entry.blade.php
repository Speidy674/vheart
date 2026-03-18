@use(\Filament\Forms\Components\Field)
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    @if($entry instanceof Field)
        <div x-data="{ state: $wire.entangle('{{ $entry->getStatePath() }}') }">
            <x-embeds.twitch x-model="state" class="inset-0" />
        </div>
    @else
        <x-embeds.twitch clip="{{ $getState() }}" class="inset-0" />
    @endif
</x-dynamic-component>
