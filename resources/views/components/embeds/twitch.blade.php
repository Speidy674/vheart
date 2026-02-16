@props(['clip' => ''])

<div
    x-data="twitchEmbed({ clip: '{{ $clip }}' })"
    x-modelable="clipId"
    {{ $attributes }}
>
    <x-embeds.base />
</div>
