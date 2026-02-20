<div
    @click="open = !open"
    aria-haspopup="true"
    :aria-expanded="open"
    {{ $attributes->merge(['class' => 'cursor-pointer inline-block']) }}
>
    {{ $slot }}
</div>
