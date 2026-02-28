<x-layout :title="__('faq.title')">
    <x-ui.card>
        <x-ui.card.content class="space-y-2">
            @forelse($questions as $question)
                <x-faq.item id="question-{{ $question->id }}" :title="$question->title">
                    {{-- replace with markdown component later --}}
                    {!! \Illuminate\Support\Str::markdown($question->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                </x-faq.item>
            @empty
                <p class="text-center">
                    {{ __('faq.no-questions') }}
                </p>
            @endforelse
        </x-ui.card.content>
    </x-ui.card>
</x-layout>
