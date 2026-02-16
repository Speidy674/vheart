@props([
    'url' => null,
    'cookieName' => null,
    'link' => null,
    'title' => null
])

<div
    x-data="baseEmbed({
        url: '{{ $url }}',
        cookieName: '{{ $cookieName }}',
        link: '{{ $link }}',
        title: '{{ $title }}'
    })"
    {{ $attributes }}
>
    <x-embeds.base />
</div>
