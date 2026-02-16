<x-layout title="VHeart Startseite">

    <div class="p-4 gap-4 grid grid-cols-2 w-full max-w-7xl m-auto">
        <x-embeds.generic url="https://www.youtube-nocookie.com/embed/videoseries?list=UUUefW5IjMaQS_ZFaG4VZi9A"/>

        <x-embeds.twitch
            clip="HelpfulAmericanPeachDxCat-2gczKKTlc6MGYrPt"
        />

        <div
            x-data="{
        clipList: @js($discover->pluck('twitch_id')->toArray(), JSON_THROW_ON_ERROR),

        init() {
            this.next();
        },

        next() {
            let currentIndex = this.clipList.indexOf(this.activeClip);
            let nextIndex = (currentIndex + 1) % this.clipList.length;

            this.activeClip = this.clipList[nextIndex];
        }
    }"
        >
            <x-embeds.twitch
                x-model="activeClip"
            />
            <div class="flex items-center justify-between">
                <h2 x-text="activeClip"></h2>
                <button @click="next()">next</button>
            </div>
        </div>
    </div>
</x-layout>
