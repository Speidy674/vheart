@props([
    'title',
    'description' => null,
])
<div {{ $attributes->twMerge('space-y-0.5') }}>
    <h2 class="text-xl font-semibold tracking-tight">{{ $title }}</h2>

    @if($description)
        <p class="text-sm text-muted-foreground">{{ $description }}</p>
    @endif
</div>
