@props(['delay' => 200])
<div
    x-data="{
        open: false,
        timeout: null,
        delay: {{ $delay }},
        show() {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => { this.open = true }, this.delay);
        },
        hide() {
            clearTimeout(this.timeout);
            this.open = false;
        }
    }"
    @mouseleave="hide()"
    @focusout="hide()"
    @keydown.escape.window="hide()"
    data-slot="tooltip"
    {{ $attributes->twMerge('inline-flex') }}
>
    {{ $slot }}
</div>
