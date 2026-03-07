@props(['clip' => null])
@if($clip?->relationLoaded('tags') && $clip?->tags->isNotEmpty())
    {{-- tags are nice to have, i dont think mobile users mind if they never see that properly --}}
    <div class="flex min-w-0 items-center gap-1.5 overflow-hidden max-h-0 opacity-0 transition-all duration-300 group-hover:mb-1 group-hover:max-h-8 group-hover:opacity-100">
        @foreach($clip->tags as $tag)
            <x-clips.preview.tag>{{ $tag->name }}</x-clips.preview.tag>
        @endforeach
    </div>
@endif
