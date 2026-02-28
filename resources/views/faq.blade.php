<x-layout :title="__('faq.title')">
    <x-ui.card>
        <x-ui.card.content
            x-data="{
                search: '',
                matches: 0,
                init() {
                    this.$watch('search', () => {
                        this.matches = 0;
                    });
                },
                isVisible(id) {
                    if (this.search.trim() === '') return true;
                    const search = this.search.trim().toLowerCase();
                    const element = document.getElementById(id);
                    if (!element) return false;

                    const result = element.textContent.toLowerCase().includes(search);
                    if(result) {
                        this.matches++;
                        return true;
                    }
                    return false;
                }
            }"
            class="space-y-6"
        >
            @if($questions->isNotEmpty())
                <div class="relative " title="{{ __('faq.search-no-javascript') }}" :title="''">
                    <x-lucide-search class="size-5 text-muted-foreground pointer-events-none absolute inset-y-0 left-4 self-center" aria-hidden="true"/>
                    <x-ui.input
                        type="search"
                        x-model.debounce.250ms="search"
                        placeholder="{{ __('faq.search-placeholder') }}"
                        aria-label="{{ __('faq.search-placeholder') }}"
                        disabled :disabled="false"
                        class="block w-full h-full rounded-xl py-3 pl-11 pr-4"
                    />
                </div>
            @endif

            <div class="space-y-2">
                @forelse($questions as $question)
                    <div x-show="isVisible('question-{{ $question->id }}')">
                        <x-faq.item id="question-{{ $question->id }}" :title="$question->title">
                            {{-- TODO: replace with markdown component later --}}
                            {!! \Illuminate\Support\Str::markdown($question->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                        </x-faq.item>
                    </div>
                @empty
                    <p class="text-center">
                        {{ __('faq.no-questions') }}
                    </p>
                @endforelse

                @if($questions->isNotEmpty())
                    <template x-if="matches < 1 && search.trim().length > 0">
                        <p class="text-center">
                            {{ __('faq.search-no-result') }}
                        </p>
                    </template>
                @endif
            </div>
        </x-ui.card.content>
    </x-ui.card>
</x-layout>
