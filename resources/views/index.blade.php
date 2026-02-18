<x-layout title="Startseite">
    <div class="m-auto w-full max-w-7xl space-y-4 p-4">
        <x-embeds.youtube url="https://www.youtube-nocookie.com/embed/videoseries?list=PLPwib1xj01i4I_TqtyrRpnrjD2oaUknOn"/>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($discover as $clip)
                <x-clips.preview :clip="$clip" class="aspect-video hover:scale-105 transition-transform" />
            @endforeach
        </div>
    </div>
</x-layout>
