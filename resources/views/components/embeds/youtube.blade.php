@props(['yid' => null, 'url' => null, 'autoplay' => false])

<div
    x-data="youtubeEmbed({
        youtubeId: {{ $yid ? "'{$yid}'" : "null" }},
        youtubeUrl: {{ $url ? "'{$url}'" : "null" }},
        autoplay: {{ $autoplay ? 'true' : 'false' }}
    })"
    x-modelable="youtubeId"
    x-modelable="youtubeUrl"
    x-modelable="autoplay"
    {{ $attributes }}
>
    <x-embeds.base />
</div>
