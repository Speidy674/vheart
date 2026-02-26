{{-- wrapper to conditionally wrap stuff in a template tag --}}
@props(['if' => true])
@if($if)
    <template {{ $attributes }}>
        {{ $slot }}
    </template>
@else
    {{ $slot }}
@endif
