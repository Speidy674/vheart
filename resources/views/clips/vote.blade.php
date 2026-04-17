<x-layout :title="__('clips.vote.page_title')" class="max-w-7xl w-full mx-auto pt-8 space-y-2" x-data="clipVote">
    <section class="w-full aspect-video h-full relative bg-black rounded-xl border border-muted shadow-sm overflow-hidden select-none">
        <template x-if="hasClip">
            <x-embeds.twitch :clip="$clip?->twitch_id ?? ''" x-model="clipTwitchId" class="h-full w-full" />
        </template>
        <template x-if="!hasClip">
            <div class="absolute inset-0 grid place-items-center text-sm text-foreground">
                {{ __('clips.vote.aside.nothing_left') }}
            </div>
        </template>

        <x-noscript-block />
    </section>

    <section
        data-clip="false"
        :data-clip="hasClip ? 'true' : 'false'"
        class="sticky bottom-18 w-full max-w-3xl mx-auto flex flex-row items-center bg-white/75 dark:bg-black/80    border border-muted    ring-black/5 ring-1 dark:ring-0    backdrop-blur-md rounded-2xl    shadow-xl dark:shadow-none    transition-all duration-300 ease-out data-[clip=false]:opacity-0 data-[clip=false]:translate-y-4 data-[clip=false]:pointer-events-none"
    >
        <div class="flex items-center gap-1 flex-1 justify-start sm:py-3 pl-2 sm:pl-4">
            <template x-if="hasBroadcaster">
                <a href="https://twitch.tv/{{ $clip->owner?->name ?? '' }}" x-bind:href="clipBroadcasterUrl" target="_blank" class="flex items-center gap-1">
                    <img src="{{ $clip?->owner?->proxiedContentUrl() ?? '' }}" alt="Avatar" x-bind:src="clipBroadcasterAvatar" class="h-6 sm:h-8 rounded-full" />
                    <span class="truncate max-w-26 sm:max-w-50" x-text="clipBroadcasterName">{{ $clip->owner?->name ?? '' }}</span>
                </a>
            </template>
            <template x-if="!hasBroadcaster">
                <x-ui.branding.logo class="h-6 sm:h-8 rounded-full" />
            </template>
        </div>

        <div class="flex shrink-0 items-center justify-center gap-3 py-2 sm:gap-4 sm:py-3">
            <div class="flex items-center gap-3 sm:gap-4">
                <div
                    data-loading="false"
                    :data-loading="isLoading ? 'true' : 'false'"
                    class="relative flex items-center gap-3 sm:gap-4 transition-opacity duration-200 data-[loading=true]:animate-pulse"
                >
                    <div
                        data-shown="true"
                        :data-shown="timeLeft > 0 ? 'true' : 'false'"
                        class="absolute -inset-1 z-10 flex items-center justify-center rounded-full bg-white/90 dark:bg-black/20    border border-muted    ring-black/5 ring-1 dark:ring-0    dark:backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 data-[shown=true]:opacity-100 data-[shown=true]:pointer-events-auto select-none"
                    >
                        <span class="col-start-1 row-start-1 text-sm font-bold text-foreground sm:text-base font-mono" x-text="Math.round(timeLeft)"></span>
                    </div>

                    <x-ui.button
                        variant="icon"
                        type="button"
                        @click="vote(1)"
                        x-bind:disabled="timeLeft > 0 || isLoading || !hasClip"
                        :disabled="!$clip"
                        :title="__('clips.vote.form.fields.vote.label')"
                        class="inline size-9 place-items-center rounded-full bg-accent/25 dark:bg-black ring-1 ring-white/10 sm:size-11 transition-transform duration-150 ease-out active:scale-95 sm:hover:scale-110 sm:hover:text-destructive group relative before:absolute before:-inset-2 before:content-[''] before:rounded-full"
                    >
                        <x-lucide-heart defer class="size-4 sm:size-5 text-accent-foreground group-hover:text-destructive transition-colors" />
                        <span class="sr-only">{{ __('clips.vote.form.fields.vote.label') }}</span>
                    </x-ui.button>

                    <x-ui.button
                        variant="icon"
                        type="button"
                        @click="vote(0)"
                        :disabled="!$clip"
                        x-bind:disabled="timeLeft > 0 || isLoading || !hasClip"
                        :title="__('clips.vote.form.fields.skip.label')"
                        class="inline size-9 place-items-center rounded-full bg-accent/25 dark:bg-black ring-1 ring-white/10 sm:size-11 transition-transform duration-150 ease-out active:scale-95 sm:hover:scale-110 group relative before:absolute before:-inset-2 before:content-[''] before:rounded-full"
                    >
                        <x-lucide-circle-x defer class="size-4 sm:size-5 text-accent-foreground group-hover:text-muted-foreground transition-colors" />
                        <span class="sr-only">{{ __('clips.vote.form.fields.skip.label') }}</span>
                    </x-ui.button>
                </div>
            </div>
        </div>

        <div class="flex-1 flex justify-end p-2 sm:py-3 pr-2 sm:pr-4">
            <x-ui.report.button
                x-model="reportItems" :items="[]"
            />
        </div>
    </section>

    @pushonce('elements')
        <script>
            const MINIMUM_RATE_LIMIT = 6;

            document.addEventListener('alpine:init', () => {
                Alpine.data('clipVote', () => ({
                    timeLeft: 0,
                    clipTwitchId: '{{ $clip?->twitch_id ?? '' }}',
                    clipId: {{ $clip?->id ?? 'null' }},
                    clipBroadcasterAvatar: '{{ $clip?->owner?->proxiedContentUrl() ?? '' }}',
                    clipBroadcasterUrl: 'https://twitch.tv/{{ $clip?->owner?->name ?? '' }}',
                    clipBroadcasterName: '{{ $clip?->owner?->name ?? '' }}',
                    hasBroadcaster: {{ $clip?->owner ? 'true' : 'false' }},
                    hasClip: {{ $clip ? 'true' : 'false' }},
                    votes: {{ $clip?->absolute_votes ?? 0 }},
                    isLoading: false,
                    timer: null,
                    reportItems: @if($clip) [{ type: 'clip', id: {{ $clip->id }}}] @else null @endif ,
                    init() {
                        this.startTimer({{ ($clip?->duration ?? 0) * 0.3 }});
                    },
                    startTimer(seconds) {
                        if(! seconds || seconds < MINIMUM_RATE_LIMIT) {
                            this.timeLeft = MINIMUM_RATE_LIMIT;
                        } else {
                            this.timeLeft = Math.round(seconds);
                        }

                        if (this.timer) clearInterval(this.timer);
                        this.timer = setInterval(() => {
                            if (this.timeLeft > 0) {
                                this.timeLeft--;
                            } else {
                                clearInterval(this.timer);
                            }
                        }, 1000);
                    },
                    async vote(decision) {
                        if (this.isLoading || !this.hasClip) return;
                        this.isLoading = true;
                        this.reportItems = [];

                        try {
                            const response = await window.axios.post('{{ route('vote.submit') }}', {
                                voted: decision
                            }, {
                                headers: { 'Accept': 'application/json' }
                            });

                            const nextClip = response.data;

                            if (nextClip && nextClip.id) {
                                this.hasClip = true;
                                this.clipTwitchId = nextClip.slug;
                                this.clipId = nextClip.id;
                                this.votes = nextClip.votes || 0;
                                this.reportItems = [{ type: 'clip', id: this.clipId}];
                                this.clipBroadcasterAvatar = nextClip.broadcaster.avatar;
                                this.clipBroadcasterUrl = 'https://twitch.tv/'+nextClip.broadcaster.name;
                                this.clipBroadcasterName = nextClip.broadcaster.name;
                                this.hasBroadcaster = nextClip.broadcaster ? true : false;
                                this.startTimer(nextClip.clip_duration * 0.3);
                            } else {
                                this.hasClip = false;
                                this.clipTwitchId = '';
                                this.clipId = null;
                                this.reportItems = null;
                                this.clipBroadcasterAvatar = '';
                                this.clipBroadcasterUrl = '';
                                this.clipBroadcasterName = '';
                                this.hasBroadcaster = false;
                            }
                        } catch (error) {
                            console.error('Failed to submit vote:', error);
                        } finally {
                            this.isLoading = false;
                        }
                    }
                }));
            })
        </script>
    @endpushonce
</x-layout>
