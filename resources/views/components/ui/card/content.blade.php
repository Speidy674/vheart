<div
    data-slot="card-content"
    {{ $attributes->merge(['class' => 'p-2 md:p-4 xl:p-6']) }}
>
    {{ $slot }}
</div>
