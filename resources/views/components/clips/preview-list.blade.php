@props(['clips' => []])
@foreach($clips as $clip)
    <x-clips.preview :clip="$clip" {{ $attributes->twMerge('aspect-video hover:scale-101 md:hover:scale-105 transition-transform') }} />
@endforeach
