<div
    x-ref="trigger"
    @mouseenter="show()"
    @focusin="show()"
    x-init="$el.hasAttribute('title') ? ($el.dataset.title = $el.getAttribute('title'), $el.removeAttribute('title')) : null"
    data-slot="tooltip-trigger"
    {{ $attributes->twMerge('inline-flex cursor-help') }}
>
    {{ $slot }}
</div>
