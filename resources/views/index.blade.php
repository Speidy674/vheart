<x-layout title="Startseite">
    <div class="m-auto w-full max-w-7xl space-y-4 p-4">
        {{-- youtube video duh --}}
        <x-embeds.youtube url="https://www.youtube-nocookie.com/embed/videoseries?list=PLPwib1xj01i4I_TqtyrRpnrjD2oaUknOn"/>

        {{-- best rated --}}
        <div
            class="flex items-center w-full relative"
            x-data="{
                isAnimating: false,
                next() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;

                    const slider = $refs.slider;
                    const item = slider.firstElementChild;
                    const itemWidth = item.offsetWidth;

                    // technically i dont need to wait for the browser, but this way its consistent with the other way
                    // and also way nicer for the browser afaik
                    requestAnimationFrame(() => {
                        slider.scrollBy({ left: itemWidth, behavior: 'smooth' });

                        slider.addEventListener('scrollend', () => {
                            slider.appendChild(item);
                            slider.scrollLeft -= itemWidth;
                            this.isAnimating = false;
                        }, { once: true });
                    });
                },

                prev() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;

                    const slider = $refs.slider;
                    const lastItem = slider.lastElementChild;
                    const itemWidth = lastItem.offsetWidth;

                    // we kinda have to use a little hack here to prevent jumping around
                    slider.style.scrollSnapType = 'none';
                    slider.style.scrollBehavior = 'auto';

                    slider.prepend(lastItem);
                    slider.scrollLeft += itemWidth;

                    // in this case we actually have to wait since we modified the DOM
                    // and triggering that now could cause jumps otherwise (especially if you spam it lol)
                    requestAnimationFrame(() => {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });

                        slider.addEventListener('scrollend', () => {
                            slider.style.scrollSnapType = '';
                            slider.style.scrollBehavior = '';
                            this.isAnimating = false;
                        }, { once: true });
                    });
                }
             }"
        >

            <button
                aria-label="Next Item"
                @click="prev()"
                {{-- funfact: after:-inset-2 with empty content makes the element a little bit larger than it actually is --}}
                {{-- in terms of usability very nice for UX as i also get annoyed by clicking 1 pixel too far if stuff is behind that --}}
                class="absolute top-1/2 left-4 z-10 -translate-y-1/2 rounded-full bg-white/25 p-2 shadow transition-transform hover:scale-110 hover:bg-accent active:scale-95 after:absolute after:-inset-2 after:content-['']"
            >
                <x-lucide-chevron-left class="size-5" defer />
            </button>

            <div
                x-ref="slider"
                class="flex w-full overflow-x-hidden snap-x snap-mandatory"
            >
                @foreach($bestRated as $clip)
                    <div class="shrink-0 snap-start w-full sm:w-1/2 lg:w-1/3 p-2">
                        <x-clips.preview :clip="$clip" class="w-full h-full block" />
                    </div>
                @endforeach
            </div>

            <button
                aria-label="Previous Item"
                @click="next()"
                class="absolute top-1/2 right-4 z-10 -translate-y-1/2 rounded-full bg-white/25 p-2 shadow transition-transform hover:scale-110 hover:bg-accent active:scale-95 after:absolute after:-inset-2 after:content-['']"
            >
                {{-- i could have used chevron-right, but we could also reuse the left variant and just rotate it so why not lol --}}
                <x-lucide-chevron-left class="size-5 rotate-180" defer />
            </button>
        </div>

        {{-- discovery --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($discover as $clip)
                <x-clips.preview :clip="$clip" class="aspect-video hover:scale-105 transition-transform" />
            @endforeach
        </div>
    </div>
</x-layout>
